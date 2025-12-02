<?php

namespace App\Services\Supplies;

use App\Interfaces\MailServiceInterface;
use App\Models\PersonalDetails;
use App\Models\RequestSupply;
use App\Models\Supply;
use App\Models\SupplyCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SupplyCategoryService
{
    protected $fileStorageService;
    private $mailService;
    public function __construct(FileStorageService $fileStorageService, MailServiceInterface $mailService)
    {
        $this->fileStorageService = $fileStorageService;
        $this->mailService = $mailService;
    }
    public function get(array $with = []): Collection
    {
        return SupplyCategory::orderBy('name', 'ASC')->get();
    }

}
