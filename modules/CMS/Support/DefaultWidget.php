<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support;

use Illuminate\View\View;
use Juzaweb\CMS\Abstracts\Widget;
use Juzaweb\CMS\Facades\ShortCode;

class DefaultWidget extends Widget
{
    /**
     * Creating widget Backend
     *
     * @param array $inputData
     * @return View|string
     */
    public function form($inputData)
    {
        $data = $this->getJsonForm();

        return view(
            'cms::backend.widget.components.widget_form',
            [
                'data' => $data,
                'key' => $inputData['key'],
                'value' => $inputData,
            ]
        );
    }

    /**
     * Creating widget front-end
     *
     * @param array $data
     * @return View|string
     */
    public function show($data)
    {
        return ShortCode::compile(
            $this->view(
                $this->data['view'],
                compact(
                    'data'
                )
            )
        );
    }

    /**
     * Updating data block
     *
     * @param array $data
     * @return array
     */
    public function update($data)
    {
        return $data;
    }
}
