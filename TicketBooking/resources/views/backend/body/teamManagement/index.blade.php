@extends('backend.master')
@section('admin')

<div class="container">
    <div class="row">
        <div class="col">
            <div class="page-title-container">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <h1 class="mb-0 pb-0 display-4" id="title">Team Section</h1>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                        <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable" data-bs-toggle="modal" data-bs-target="#addEditModal" id="addNewButton">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-plus undefined">
                                <path d="M10 17 10 3M3 10 17 10"></path>
                            </svg>
                            <span>Add New</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Modal -->
            <div class="modal modal-right fade" id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add New</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="teamForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="teamId" name="id">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <span id="name_error" class="text-danger"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" required>
                                    <span id="position_error" class="text-danger"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <img id="showEditImage" src="" alt="" width="50">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary save_team" id="addEditConfirmButton">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="showData">
                    <!-- Data will be inserted here by AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

    // Function to show ajax validation error
    function showError(name, message) {
        $(name).addClass('is-invalid');
        $(name).focus();
        $(`${name}_error`).show().text(message);
    }

    function validateForm() {
        let isValid = true;

        // Clear previous error messages
        $('.text-danger').text('');
        $('input').css('border-color', '');

        // Get form values
        let name = $('#name').val().trim();
        let position = $('#position').val().trim();
        let image = $('#image').val();

        // Validate name
        if (name === '') {
            showError('#name', 'Name is required.');
            isValid = false;
        } else if (name.length > 100) {
            showError('#name', 'Name cannot be longer than 100 characters.');
            isValid = false;
        }

        // Validate position
        if (position === '') {
            showError('#position', 'Position is required.');
            isValid = false;
        } else if (position.length > 100) {
            showError('#position', 'Position cannot be longer than 100 characters.');
            isValid = false;
        }

        // Validate image (if needed)
        if (image) {
            let fileExtension = image.split('.').pop().toLowerCase();
            let validExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            if (!validExtensions.includes(fileExtension)) {
                showError('#image', 'Invalid image format. Allowed formats: jpeg, jpg, png, gif, webp.');
                isValid = false;
            }
        }

        return isValid;
    }


    $(document).ready(function() {
        


        // Function to handle form submission
        function handleFormSubmission(url, type, formData) {
            if (!validateForm()) {
                return; // If the form is invalid, do not proceed with the AJAX request
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                type: type,
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        $('#addEditModal').modal('hide');
                        $('.teamForm')[0].reset();
                        toastr.success(res.message);
                        teamView(); // Refresh the table data
                    } 
                },
                error: function(err) {
                    if (err.status === 422) { // Validation error
                        var errors = err.responseJSON.errors;
                        if (errors.name) {
                            showError('#name', errors.name);
                        }
                        if (errors.position) {
                            showError('#position', errors.position);
                        }
                        // Handle other validation errors similarly
                    } else {
                        console.error("Error in AJAX request:", err);
                        toastr.error("An unexpected error occurred.");
                    }
                }
            });
        }

        // Event listener for save button
        $('.save_team').on('click', function(e) {
            e.preventDefault();
            let formData = new FormData($('.teamForm')[0]);
            let id = $('#teamId').val();

            if (id) {
                handleFormSubmission(`/backend/team/update/${id}`, 'POST', formData);
            } else {
                handleFormSubmission('{{ route("backend.team.add") }}', 'POST', formData);
            }
        });

        // Function to refresh the team data table
        function teamView() {
            $.ajax({
                url: '{{ route("backend.team.data") }}',
                method: 'GET',
                success: function(res) {
                    if (res.status == 200) {
                        const teams = res.data;
                        // console.log(res.data);
                        $('.showData').empty();
                        if (teams.length > 0) {
                            $.each(teams, function(index, team) {
                                const tr = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        
                                        <td>${team.image ? `<img src="/uploads/team/${team.image}" alt="Team Image" width="50">` : 'photo not found'}</td>
                                        <td>${team.name ?? ""}</td>
                                        <td>${team.position ?? ""}</td>
                                        <td>
                                            <a href="#" class="btn btn-outline-primary btn-icon team_edit" data-id="${team.id}" data-bs-toggle="modal" data-bs-target="#addEditModal">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-edit-square undefined"><path d="M11 2L5.5 2C4.09554 2 3.39331 2 2.88886 2.33706C2.67048 2.48298 2.48298 2.67048 2.33706 2.88886C2 3.39331 2 4.09554 2 5.5L2 14.5C2 15.9045 2 16.6067 2.33706 17.1111C2.48298 17.3295 2.67048 17.517 2.88886 17.6629C3.39331 18 4.09554 18 5.5 18L14.5 18C15.9045 18 16.6067 18 17.1111 17.6629C17.3295 17.517 17.517 17.3295 17.6629 17.1111C18 16.6067 18 15.9045 18 14.5L18 11"></path><path d="M15.4978 3.06224C15.7795 2.78052 16.1616 2.62225 16.56 2.62225C16.9585 2.62225 17.3405 2.78052 17.6223 3.06224C17.904 3.34396 18.0623 3.72605 18.0623 4.12446C18.0623 4.52288 17.904 4.90497 17.6223 5.18669L10.8949 11.9141L8.06226 12.6223L8.7704 9.78966L15.4978 3.06224Z"></path></svg>
                                            </a>
                                            <a href="#" class="btn btn-outline-danger btn-icon team_delete" data-id="${team.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-bin undefined"><path d="M4 5V14.5C4 15.9045 4 16.6067 4.33706 17.1111C4.48298 17.3295 4.67048 17.517 4.88886 17.6629C5.39331 18 6.09554 18 7.5 18H12.5C13.9045 18 14.6067 18 15.1111 17.6629C15.3295 17.517 15.517 17.3295 15.6629 17.1111C16 16.6067 16 15.9045 16 14.5V5"></path><path d="M14 5L13.9424 4.74074C13.6934 3.62043 13.569 3.06028 13.225 2.67266C13.0751 2.50368 12.8977 2.36133 12.7002 2.25164C12.2472 2 11.6734 2 10.5257 2L9.47427 2C8.32663 2 7.75281 2 7.29981 2.25164C7.10234 2.36133 6.92488 2.50368 6.77496 2.67266C6.43105 3.06028 6.30657 3.62044 6.05761 4.74074L6 5"></path><path d="M2 5H18M12 9V13M8 9V13"></path></svg>
                                            </a>
                                        </td>
                                    </tr>`;
                                $('.showData').append(tr);
                            });
                        } else {
                            $('.showData').html(`
                                <tr>
                                    <td colspan="6" class="text-center text-warning mb-2">Data Not Found</td>
                                    <td class="text-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditModal">Add Team Info <i data-feather="plus"></i></button>
                                    </td>
                                </tr>`);
                        }
                    } else {
                        console.error("Failed to fetch team data:", res.message);
                        toastr.warning(res.message);
                    }
                },
                error: function(err) {
                    console.error("Error in fetching team data:", err);
                    toastr.error("An unexpected error occurred.");
                }
            });
        }

        // Initial load of team data
        teamView();

        // Event listener for add new button
        $('#addNewButton').click(function() {
            $('#modalTitle').text('Add New');
            $('#teamId').val('');
            $('#name').val('');
            $('#position').val('');
            $('#showEditImage').attr('src', '');
            $('#addEditConfirmButton').text('Add');
        });

        // Event listener for edit button
        $(document).on('click', '.team_edit', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            // alert(id);
            $.ajax({
                url: `/backend/team/edit/${id}`,
                type: 'GET',
                success: function(data) {
                    // console.log(data);
                    if (data.status === 200) {
                        // alert("hello world");
                        const team = data.data;
                        $('#modalTitle').text('Edit Team Member');
                        $('#teamId').val(team.id);
                        $('#name').val(team.name);
                        $('#position').val(team.position);
                        $('#showEditImage').attr('src', team.photo_url ? `/uploads/team/${team.photo_url}` : '');
                        $('#addEditConfirmButton').text('Update');
                    } else {
                        toastr.warning(res.message);
                    }
                },
                error: function(err) {
                    console.error("Error in fetching team data:", err);
                    toastr.error("An unexpected error occurred.");
                }
            });
        });
        // Event listener for delete button
        $(document).on('click', '.team_delete', function(e) {
            e.preventDefault();
            let id = this.getAttribute('data-id');
            // alert(id);
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to delete this team member!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: `/backend/team/destroy/${id}`,
                        type: 'GET',
                        success: function(res) {
                            // console.log(res);
                            if (res.status == 200) {
                                toastr.success(res.message);
                                teamView();
                            } else {
                                toastr.warning(res.message);
                                toastr.warning(res.message);
                            }
                        },
                        error: function(err) {
                            // console.log(err);
                            console.error("Error in deleting team member:", err);
                            toastr.error("An unexpected error occurred.");
                            // toastr.warning("Error in deleting team member:", err);
                        }
                    });
                }
            });
        });

    });
</script>
@endsection