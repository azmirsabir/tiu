<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Files</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
        }
        .page {
            width: 210mm;
            height: 297mm;
            page-break-after: always;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .page img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>

@foreach($files as $file)
    <div class="page">
        <img src="{{ asset('storage/files/' . $file->path) }}" alt="Image">
    </div>
@endforeach

</body>
</html>
