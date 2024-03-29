<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Request;

use App\Traits\SitePermissionTrait;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class SeoRequest extends FormRequest
{
    use SitePermissionTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'id' => 'numeric',
            'keywords' => 'max:255',
            'site_id' => [
                'required',
                'numeric',
                Rule::exists('sites', 'id'),
            ],
        ];

        $siteIds = $this->getSiteIds();
        if (! empty($siteIds)) {
            $rules['site_id'][] = Rule::in($siteIds);
        }

        return $rules;
    }
}
