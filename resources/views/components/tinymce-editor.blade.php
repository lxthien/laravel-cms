@props(['id' => 'tinymce', 'value' => ''])

<textarea 
    id="{{ $id }}" 
    name="content" 
    {{ $attributes->merge(['class' => 'form-control']) }}
>{{ $value }}</textarea>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/8gma6mvzjqqnn5p4u29fi9ewfme5p5kzrmqatg4x6wpwyjxr/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
    tinymce.init({
        selector: '#{{ $id }}',
        height: 500,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        language: 'vi_VN',
        uploadcare_public_key: '10ea031b4f084f7af201',
    });
</script>
@endpush