<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Controllers\Backend;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Juzaweb\Backend\Events\AddFolderSuccess;
use Juzaweb\Backend\Http\Requests\Media\AddFolderRequest;
use Juzaweb\Backend\Http\Requests\Media\UpdateRequest;
use Juzaweb\Backend\Repositories\MediaFileRepository;
use Juzaweb\Backend\Repositories\MediaFolderRepository;
use Juzaweb\CMS\Facades\Facades;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\Backend\Models\MediaFile;
use Juzaweb\Backend\Models\MediaFolder;

class MediaController extends BackendController
{
    protected string $template = self::INERTIA_TEMPLATE;

    public function __construct(
        protected MediaFileRepository $fileRepository,
        protected MediaFolderRepository $folderRepository
    ) {
    }

    public function index(Request $request, $folderId = null): View|Response
    {
        $title = trans('cms::app.media');
        $type = $request->get('type', 'file');

        if ($folderId) {
            $this->addBreadcrumb(
                [
                    'title' => $title,
                    'url' => route('admin.media.index'),
                ]
            );

            $folder = $this->folderRepository->find($folderId);
            $folder->load('parent');
            $this->addBreadcrumbFolder($folder);
            $title = $folder->name;
        }

        $query = collect($request->query());
        $mediaFolders = collect([]);
        if ($request->input('page', 1) == 1) {
            $mediaFolders = $this->getDirectories($query, $folderId);
        }

        $mediaFiles = $this->getFiles($query, 36 - $mediaFolders->count(), $folderId);
        $maxSize = config("juzaweb.filemanager.types.{$type}.max_size");
        $mimeTypes = config("juzaweb.filemanager.types.{$type}.valid_mime");
        if (empty($mimeTypes)) {
            $mimeTypes = config("juzaweb.filemanager.types.file.valid_mime");
        }

        return $this->view(
            'cms::backend.media.index',
            [
                'fileTypes' => $this->getFileTypes(),
                'folderId' => $folderId,
                'mediaFolders' => $mediaFolders,
                'mediaFiles' => $mediaFiles,
                'title' => $title,
                'mimeTypes' => $mimeTypes,
                'type' => $type,
                'maxSize' => $maxSize,
            ]
        );
    }

    public function update(UpdateRequest $request, $id): JsonResponse|RedirectResponse
    {
        if ($request->input('is_file')) {
            $model = $this->fileRepository->find($id);
        } else {
            $model = $this->folderRepository->find($id);
        }

        DB::beginTransaction();
        try {
            $model->update($request->only(['name']));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->success(trans('cms::app.updated_successfully'));
    }

    public function download($id): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $model = $this->fileRepository->find($id);
        $storage = Storage::disk(config('juzaweb.filemanager.disk'));
        if (!$storage->exists($model->path)) {
            abort(404, 'File not exists.');
        }

        return response()->download($storage->path($model->path));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        if ($request->input('is_file')) {
            $model = $this->fileRepository->find($id);
        } else {
            $model = $this->folderRepository->find($id);
        }

        DB::beginTransaction();
        try {
            $model->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->success(trans('cms::app.deleted_successfully'));
    }

    public function addFolder(AddFolderRequest $request): JsonResponse|RedirectResponse
    {
        DB::beginTransaction();
        try {
            $folder = MediaFolder::create($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        event(new AddFolderSuccess($folder));

        return $this->success(
            trans('cms::filemanager.add-folder-successfully')
        );
    }

    protected function getFileTypes()
    {
        return config('juzaweb.filemanager.types');
    }

    protected function addBreadcrumbFolder($folder): void
    {
        $parent = $folder->parent;
        if ($parent) {
            $this->addBreadcrumb(
                [
                    'title' => $parent->name,
                    'url' => route('admin.media.folder', $parent->id),
                ]
            );

            $parent->load('parent');
            if ($parent->parent) {
                $this->addBreadcrumbFolder($parent);
            }
        }
    }

    /**
     * Get files in folder
     *
     * @param  Collection  $sQuery
     * @param  int  $limit
     * @param  int|null  $folderId
     * @return LengthAwarePaginator
     */
    protected function getFiles(Collection $sQuery, int $limit = 40, ?int $folderId = null): LengthAwarePaginator
    {
        $query = MediaFile::whereFolderId($folderId);

        if ($sQuery->get('type')) {
            $extensions = $this->getTypeExtensions($sQuery->get('type'));
            $query->whereIn('extension', $extensions);
        }

        $query->orderBy('id', 'DESC');

        return $query->paginate($limit);
    }

    /**
     * Get directories in folder
     *
     * @param  Collection  $sQuery
     * @param  int|null  $folderId
     * @return EloquentCollection
     */
    protected function getDirectories(Collection $sQuery, ?int $folderId): EloquentCollection
    {
        return MediaFolder::whereFolderId($folderId)->get();
    }

    protected function getTypeExtensions(string $type)
    {
        $extensions = config("juzaweb.filemanager.types.{$type}.extensions");
        if (empty($extensions)) {
            $extensions = match ($type) {
                'file' => Facades::defaultFileExtensions(),
                'image' => Facades::defaultImageExtensions(),
            };
        }

        return $extensions;
    }
}
