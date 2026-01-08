<?php

namespace App\Contracts;

interface MessageRepositoryInterface
{
    /**
     * Save inspections to storage
     *
     * @return string Path to saved file
     */
    public function saveInspections(array $inspections): string;

    /**
     * Save failure reports to storage
     *
     * @return string Path to saved file
     */
    public function saveFailureReports(array $failureReports): string;

    /**
     * Save unprocessed messages to storage
     *
     * @return string Path to saved file
     */
    public function saveUnprocessedMessages(array $unprocessedMessages): string;
}
