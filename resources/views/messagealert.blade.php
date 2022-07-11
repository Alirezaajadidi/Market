<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include this in your blade layout -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</head>
<body>
@include('sweetalert::alert')
</body>
</html>
