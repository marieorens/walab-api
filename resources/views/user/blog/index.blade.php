@extends('layout')

@section('page_content')

    <style>
        /* CSS pour les images (on garde ça, c'est propre) */
        .blog-img-box {
            width: 80px;
            height: 60px;
            overflow: hidden;
            border-radius: 8px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .blog-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* CSS pour s'assurer que les boutons sont bien ronds si le thème ne le fait pas */
        .btn-circle {
            width: 35px;
            height: 35px;
            padding: 0;
            border-radius: 50%;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
        }

        .table-data-feature {
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="container-fluid">

        <!-- Titre et Fil d'ariane -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Walab</a></li>
                            <li class="breadcrumb-item active">Blog</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Gestion du Blog</h4>
                </div>
            </div>
        </div>

        <!-- Zone d'action (Bouton Ajouter) -->
        <div class="row">
            <div class="table-data__tool">
                <div class="table-data__tool-right text-end mb-3">
                    <a data-bs-toggle="modal" data-bs-target="#createModal">
                        <button class="btn btn-primary">
                            <i class="zmdi zmdi-plus"></i> Ajouter un article
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Alertes -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-left: auto; margin-right: auto; max-width: fit-content;">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Liste des articles -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="p-3">
                            <h5 class="header-title mb-0">Listes des articles</h5>
                        </div>

                        <div class="table-responsive table-data">
                            <table class="table table-nowrap table-hover mb-0">
                                <thead class="bg-primary">
                                <tr>
                                    <th class="text-white text-center">Image</th>
                                    <th class="text-white">Titre</th>
                                    <th class="text-white">Extrait</th>
                                    <th class="text-white text-center">Date</th>
                                    <th class="text-white text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($blogs as $item)
                                    <tr>
                                        <!-- Image -->
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="blog-img-box shadow-sm border border-light">
                                                    @if($item->image)
                                                        <img src="{{ asset($item->image) }}" alt="img">
                                                    @else
                                                        <i class="mdi mdi-image-off text-muted"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Titre -->
                                        <td class="align-middle fw-bold">
                                            {{ $item->title }}
                                        </td>

                                        <!-- Extrait -->
                                        <td class="align-middle">
                                            {{ Str::limit($item->content, 50) }}
                                        </td>

                                        <!-- Date -->
                                        <td class="align-middle text-center">
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </td>

                                        <!-- Actions (Style restauré) -->
                                        <td class="align-middle text-center">
                                            <div class="table-data-feature">
                                                <!-- Bouton Modifier (Noir rond) -->
                                                <a data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}" class="btn btn-dark btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="bi bi-pencil-square text-white"></i>
                                                </a>

                                                <!-- Bouton Supprimer (Rouge rond) -->
                                                <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i class="bi bi-trash text-white"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- ==================== MODALES ==================== -->

                                    <!-- MODAL MODIFIER -->
                                    <div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modification</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('blog.update', $item->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label class="form-label">Titre</label>
                                                            <input type="text" name="title" class="form-control rounded-pill focus-ring" value="{{ $item->title }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Contenu</label>
                                                            {{-- Quill editor container (update) --}}
                                                            <div id="editor-update-{{ $item->id }}" class="quill-editor" style="height:300px;">{!! $item->content !!}</div>
                                                            <input type="hidden" name="content" id="content-input-{{ $item->id }}" value="{!! e($item->content) !!}">
                                                        </div>
                                                        <div class="row mb-3 align-items-center">
                                                            <div class="col-auto">
                                                                <div class="blog-img-box">
                                                                    <img src="{{ asset($item->image) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <label class="form-label">Changer l'image</label>
                                                                <input type="file" name="image" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- MODAL SUPPRIMER -->
                                    <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmer la suppression</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer cet article ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <a href="{{ route('blog.destroy', $item->id) }}">
                                                        <button type="button" class="btn btn-danger">Confirmer</button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3 px-3">
                            {{ $blogs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ==================== MODAL CREATION ==================== -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvel Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" name="title" class="form-control rounded-pill focus-ring" placeholder="Titre...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenu</label>
                            {{-- Quill editor container (create) --}}
                            <div id="editor-create" class="quill-editor" style="height:350px;"></div>
                            <input type="hidden" name="content" id="content-input-create">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Image upload handler used by editors
            async function uploadImageFile(file) {
                const fd = new FormData();
                fd.append('image', file);

                try {
                    const res = await fetch('{{ url('/api/blog/upload-image') }}', {
                        method: 'POST',
                        body: fd,
                        // include credentials in case the API requires session cookies
                        credentials: 'same-origin',
                        headers: {
                            // prevent CORS/content-type override; let browser set multipart boundary
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!res.ok) {
                        const text = await res.text();
                        console.error('Upload failed, status:', res.status, text);
                        return null;
                    }

                    const json = await res.json();
                    if (json && json.url) return json.url;
                } catch (e) {
                    console.error('Upload failed', e);
                }
                return null;
            }

            function createImageHandler(quill) {
                return function imageHandler() {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.click();
                    input.onchange = async () => {
                        const file = input.files[0];
                        if (!file) return;
                        const range = quill.getSelection(true);
                        // insert temporary loading placeholder
                        quill.insertEmbed(range.index, 'image', '/images/loading.gif');
                        const url = await uploadImageFile(file);
                        if (url) {
                            // remove placeholder and insert real image
                            quill.deleteText(range.index, 1);
                            quill.insertEmbed(range.index, 'image', url);
                            quill.setSelection(range.index + 1);
                        } else {
                            alert('Échec du téléversement de l\'image');
                        }
                    };
                };
            }

            // Initialize create editor
            const editorCreateEl = document.getElementById('editor-create');
            if (editorCreateEl) {
                const quillCreate = new Quill('#editor-create', {
                    theme: 'snow',
                    modules: { toolbar: [
                        [{ header: [1,2,3,false] }],
                        ['bold','italic','underline','strike'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link','image'], ['clean']
                    ] }
                });
                // override image handler
                const toolbar = quillCreate.getModule('toolbar');
                toolbar.addHandler('image', createImageHandler(quillCreate));

                // on submit copy HTML
                const createForm = document.querySelector('#createModal form');
                if (createForm) {
                    createForm.addEventListener('submit', function (e) {
                        try {
                            const html = quillCreate.root.innerHTML;
                            document.getElementById('content-input-create').value = html;
                            console.log('[Blog Admin] Submitting create form. Content length:', html.length);
                            // Also log title and whether image file is present for debugging
                            const titleEl = createForm.querySelector('input[name="title"]');
                            const fileEl = createForm.querySelector('input[name="image"]');
                            console.log('[Blog Admin] title=', titleEl ? titleEl.value : '(none)', 'hasImage=', fileEl && fileEl.files && fileEl.files.length > 0);
                        } catch (err) {
                            console.error('Error preparing create form for submit', err);
                        }
                        // allow form to proceed normally (no preventDefault)
                    });
                }
            }

            // Initialize update editors (one per modal)
            document.querySelectorAll('[id^="editor-update-"]').forEach(function (el) {
                const id = el.id.replace('editor-update-', '');
                const quill = new Quill('#' + el.id, {
                    theme: 'snow',
                    modules: { toolbar: [
                        [{ header: [1,2,3,false] }],
                        ['bold','italic','underline','strike'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link','image'], ['clean']
                    ] }
                });
                const toolbar = quill.getModule('toolbar');
                toolbar.addHandler('image', createImageHandler(quill));

                // When the corresponding update form is submitted, copy content
                const form = document.querySelector('#updateModal' + id + ' form');
                if (form) {
                    form.addEventListener('submit', function (e) {
                        const html = quill.root.innerHTML;
                        const hidden = document.getElementById('content-input-' + id);
                        if (hidden) hidden.value = html;
                    });
                }
            });
        });
    </script>
@endpush
