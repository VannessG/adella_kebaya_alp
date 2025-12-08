<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Cabang - Adella Kebaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .branch-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(113, 63, 9, 0.2);
        }
        .btn-branch {
            background-color: #713f09;
            color: white;
            border: 2px solid #713f09;
        }
        .btn-branch:hover {
            background-color: #5a3207;
            border-color: #5a3207;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="mb-5">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 120px;" class="mb-4">
                    <h1 class="display-4 fw-bold" style="color: #3a2611;">Pilih Cabang</h1>
                    <p class="lead text-muted">Silakan pilih cabang terdekat untuk melanjutkan</p>
                </div>

                <div class="row g-4">
                    @foreach($branches as $branch)
                    <div class="col-md-6">
                        <div class="card branch-card border-0 shadow-lg" 
                             onclick="selectBranch({{ $branch->id }})"
                             style="border: 3px solid transparent; border-radius: 15px;">
                            <div class="card-body p-4 text-center">
                                <div class="mb-3">
                                    <i class="bi bi-shop display-4" style="color: #713f09;"></i>
                                </div>
                                <h3 class="card-title fw-bold" style="color: #3a2611;">{{ $branch->city }}</h3>
                                <p class="card-text text-muted">
                                    <i class="bi bi-geo-alt"></i> {{ $branch->address }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-telephone"></i> {{ $branch->phone }}
                                </p>
                                <button class="btn btn-branch btn-lg w-100" 
                                        onclick="event.stopPropagation(); selectBranch({{ $branch->id }})">
                                    Pilih Cabang Ini
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    <p class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        Anda dapat mengubah cabang nanti melalui halaman profil
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form id="branchForm" action="{{ route('branch.select') }}" method="POST">
        @csrf
        <input type="hidden" name="branch_id" id="selectedBranch">
    </form>

    <script>
        function selectBranch(branchId) {
            document.getElementById('selectedBranch').value = branchId;
            document.getElementById('branchForm').submit();
        }
    </script>
</body>
</html>