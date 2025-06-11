# Algolia Doctor Search Implementation

## Overview

This implementation adds intelligent symptom-based doctor search using Algolia to the appointment booking system. Patients can search for symptoms (e.g., "headache", "stomach pain") and get relevant specialists, with general consultants as fallback options.

## Features

1. **Symptom-to-Specialist Matching**: Maps common symptoms to relevant medical specializations
2. **Algolia Search Integration**: Fast, typo-tolerant search with advanced ranking
3. **Intelligent Fallback**: Database search when Algolia is unavailable
4. **General Consultant Fallback**: Shows general practitioners when no specialists match
5. **Enhanced User Feedback**: Provides contextual search feedback to users

## Installation & Setup

### 1. Install Dependencies
```bash
composer require laravel/scout algolia/algoliasearch-client-php
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

### 2. Configure Algolia
Add to your `.env` file:
```env
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your_app_id
ALGOLIA_SECRET=your_secret_key
ALGOLIA_SEARCH=your_search_key
```

### 3. Sync Doctors to Algolia
```bash
# Initial sync with fresh index
php artisan doctors:sync-algolia --fresh

# Regular sync (updates only)
php artisan doctors:sync-algolia
```

## How It Works

### 1. Doctor Model Enhancement
- Added `Searchable` trait from Laravel Scout
- Implemented `toSearchableArray()` with symptom keywords
- Added specialization-to-symptom mapping

### 2. Search Service (`DoctorSearchService`)
- **Primary**: Algolia search with symptom matching
- **Fallback**: Database search with LIKE queries
- **Enhancement**: General consultant suggestions

### 3. Search Flow
1. User enters search term (e.g., "headache")
2. Algolia searches across:
   - Doctor names
   - Specializations
   - Bio descriptions
   - Symptom keywords
3. Results prioritized by:
   - Symptom-specialization relevance
   - Experience years
   - Consultation fees
4. General consultants added if < 3 results
5. Fallback to database search if Algolia fails

### 4. Symptom Mapping Examples
```php
'Cardiology' => [
    'chest pain', 'heart palpitations', 'shortness of breath',
    'high blood pressure', 'irregular heartbeat'
],
'Neurology' => [
    'headache', 'migraine', 'seizures', 'memory loss',
    'dizziness', 'numbness', 'tingling'
],
'Gastroenterology' => [
    'stomach pain', 'nausea', 'vomiting', 'diarrhea',
    'constipation', 'acid reflux', 'heartburn'
]
```

## Frontend Enhancements

### 1. Enhanced Search Feedback
- Detects symptom-based queries
- Shows specialized feedback messages
- Explains search results context

### 2. Error Handling
- Graceful fallback on network errors
- Retry mechanisms
- User-friendly error messages

## API Changes

### Enhanced `searchDoctors` Endpoint
**URL**: `/api/appointments/search-doctors`

**New Response Format**:
```json
{
  "success": true,
  "doctors": [...],
  "total": 5,
  "search_type": "search|all",
  "query": "headache"
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Search temporarily unavailable",
  "doctors": [],
  "total": 0
}
```

## Configuration

### Algolia Index Settings
```php
'doctors' => [
    'searchableAttributes' => [
        'user_name',
        'specialization', 
        'bio',
        'service_names',
        'symptom_keywords',
        'searchable_content'
    ],
    'customRanking' => [
        'desc(experience_years)',
        'asc(consultation_fee)'
    ]
]
```

## Testing

### 1. Test Script
Run the test script to verify integration:
```bash
php test-scripts/test-algolia-search.php
```

### 2. Manual Testing
1. Search for symptoms: "headache", "chest pain", "stomach ache"
2. Search for specializations: "cardiology", "neurology"
3. Search for doctor names
4. Test with typos: "headake", "caridology"

## Monitoring & Maintenance

### 1. Sync Monitoring
- Monitor sync command success/failures
- Set up alerts for Algolia API errors
- Regular index health checks

### 2. Search Analytics
- Track popular search terms
- Monitor fallback usage rates
- Analyze search success rates

### 3. Performance
- Algolia provides sub-50ms search response times
- Database fallback adds ~100-300ms
- Frontend shows loading states for better UX

## Troubleshooting

### Common Issues

1. **No Search Results**
   - Verify Algolia credentials in `.env`
   - Run sync command: `php artisan doctors:sync-algolia --fresh`
   - Check doctor `is_available` status

2. **Slow Search**
   - Check Algolia API status
   - Verify network connectivity
   - Monitor database fallback usage

3. **Sync Failures**
   - Verify Algolia app limits
   - Check record quota usage
   - Validate index permissions

### Debug Commands
```bash
# Clear and rebuild index
php artisan doctors:sync-algolia --fresh

# Check Scout status
php artisan scout:status

# Test search functionality
php test-scripts/test-algolia-search.php
```

## Future Enhancements

1. **Machine Learning**: Train models on successful appointment patterns
2. **Geolocation**: Add distance-based ranking
3. **Availability**: Real-time slot availability in search results
4. **Personalization**: Learn from user's previous searches/bookings
5. **Voice Search**: Support for voice-based symptom queries

## Security Considerations

1. **API Keys**: Store Algolia keys securely, use search-only keys for frontend
2. **Data Privacy**: Ensure patient data isn't indexed unnecessarily
3. **Rate Limiting**: Implement search rate limiting to prevent abuse
4. **Input Sanitization**: Validate and sanitize all search inputs
