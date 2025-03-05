<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\Micro\Http\Schema\FindDocumentSchema;
use App\Domain\UMAC\Model\Documents;
use Illuminate\Http\Request;

/**
 * @property-read Documents $resource
 */
class FindDocumentResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new FindDocumentSchema(
            documentType: $this->resource->documentType,
            serialNumber: $this->resource->serialNumber,
            citizenship: $this->resource->citizenship,
            dateIssue: $this->resource->dateIssue,
            dateExpiry: $this->resource->dateExpiry,
            photo: $this->resource->photo,
        );
    }
}
