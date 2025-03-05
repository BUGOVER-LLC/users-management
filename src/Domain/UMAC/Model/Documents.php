<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Model;

use App\Core\Enum\DocumentStatus;
use App\Core\Enum\DocumentType;
use App\Core\FileSystem\Casts\Attachments;
use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Repository\DocumentRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property int $documentId
 * @property int $citizenId
 * @property DocumentType $documentType
 * @property DocumentStatus $documentStatus
 * @property string $serialNumber
 * @property string $citizenship
 * @property string $dateIssue
 * @property string|null $dateExpiry
 * @property string $authority
 * @property array|null $photo
 * @property \Illuminate\Support\Carbon $createdAt
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Documents newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents query()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereCitizenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereCitizenship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereDateExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereDateIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereDocumentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereSerialNumber($value)
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property \Illuminate\Support\Carbon $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|Documents onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documents withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Documents withoutTrashed()
 * @property-read Citizen $citizen
 * @mixin \Eloquent
 */
#[ModelEntity(repositoryClass: DocumentRepository::class)]
final class Documents extends ServiceModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'Documents';

    /**
     * @var string
     */
    protected $primaryKey = 'documentId';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'citizenId',
        'documentType',
        'documentStatus',
        'serialNumber',
        'citizenship',
        'dateIssue',
        'dateExpiry',
        'authority',
        'photo',
    ];

    /**
     * @var string
     */
    protected string $map = 'document';

    /**
     * @var array<string, class-string>
     */
    protected $casts = [
        'documentType' => DocumentType::class,
        'documentStatus' => DocumentStatus::class,
        'photo' => Attachments::class,
    ];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'citizenId', 'citizenId');
    }
}
