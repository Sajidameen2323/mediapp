<?php

namespace App\Observers;

use App\Models\LabTestRequest;

class LabTestRequestObserver
{
    /**
     * Handle the LabTestRequest "created" event.
     */
    public function created(LabTestRequest $labTestRequest): void
    {
        // Automatically create author access for the ordering doctor
        $labTestRequest->createAuthorAccess();
    }

    /**
     * Handle the LabTestRequest "updated" event.
     */
    public function updated(LabTestRequest $labTestRequest): void
    {
        //
    }

    /**
     * Handle the LabTestRequest "deleted" event.
     */
    public function deleted(LabTestRequest $labTestRequest): void
    {
        //
    }

    /**
     * Handle the LabTestRequest "restored" event.
     */
    public function restored(LabTestRequest $labTestRequest): void
    {
        //
    }

    /**
     * Handle the LabTestRequest "force deleted" event.
     */
    public function forceDeleted(LabTestRequest $labTestRequest): void
    {
        //
    }
}
