<?php

namespace App\Enums;

enum CandidateNoteVisibility: string
{
    case Public = 'public';
    case Private = 'private';
}