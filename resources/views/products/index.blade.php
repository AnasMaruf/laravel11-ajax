<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Laravel 11 AJAX</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous"
        />
        <link
            rel="stylesheet"
            href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css"
        />
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand" href="#">Laravel 11 Ajax</a>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a
                                class="nav-link active"
                                aria-current="page"
                                href="#"
                                >Home</a
                            >
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true"
                                >Disabled</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-3">
            <div class="card">
                <div class="card-header">Table Product</div>
                <div class="card-body">
                    <button
                        class="btn btn-primary mb-2"
                        onclick="showModal()"
                        data-bs-target="#productModal"
                    >
                        + Add Data
                    </button>
                    <table
                        class="table table-bordered table-striped"
                        id="tableProduct"
                    >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Slug Url</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('products.modal')
        <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"
        ></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"
        ></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
        <!-- Laravel Javascript Validation -->
        <script
            type="text/javascript"
            src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"
        ></script>

        {!! JsValidator::formRequest('App\Http\Requests\ProductRequest',
        'productForm') !!}
        <script>
            let save_method;
            $(document).ready(function () {
                productsTable();
            });
            function productsTable() {
                $("#tableProduct").DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "products/dataTable",
                    order: [[0, "desc"]],
                    columns: [
                        {
                            data: "DT_RowIndex",
                            name: "DT_RowIndex",
                        },
                        {
                            data: "name",
                            name: "name",
                        },
                        {
                            data: "slug",
                            name: "slug",
                        },
                        {
                            data: "description",
                            name: "description",
                        },
                        {
                            data: "price",
                            name: "price",
                        },
                        {
                            data: "image",
                            name: "image",
                        },
                        {
                            data: "action",
                            name: "action",
                        },
                    ],
                });
            }

            function resetValidation() {
                $(".is-invalid").removeClass("is-invalid");
                $(".is-valid").removeClass("is-valid");
                $("span.invalid-feedback").removeClass("d-block");
                $("span.invalid-feedback").text("");
            }
            function showModal() {
                $("#productForm")[0].reset();
                resetValidation();
                $("#productModal").modal("show");
                save_method = "create";
                $(".modal-title").text("Create New Product");
                $(".btnSubmit").text("Add Product");
            }

            $("#productModal").on("hidden.bs.modal", function () {
                resetValidation();
            });

            //Add Product
            $("#productForm").on("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                let url, method;
                url = "products";
                method = "POST";
                if (save_method === "update") {
                    url = "products/" + $("#id").val();
                    formData.append("_method", "PUT");
                }
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    type: method,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $("#productModal").modal("hide");
                        $("#tableProduct").DataTable().ajax.reload();
                        Swal.fire({
                            title: response.title,
                            text: response.text,
                            icon: response.icon,
                            // showButtonText: false,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // console.log(jqXHR.responseText);
                        // alert(jqXHR.responseText);
                        // Clear previous error messages
                        const errors = jqXHR.responseJSON.errors;
                        $(".invalid-feedback").text("");
                        $("input").removeClass("is-invalid");

                        // Display error messages
                        if (errors.name) {
                            $("#name").addClass("is-invalid");
                            $("#error-name")
                                .text(errors.name[0])
                                .addClass("d-block");
                        }
                        if (errors.description) {
                            $("#description").addClass("is-invalid");
                            $("#error-description")
                                .text(errors.description[0])
                                .addClass("d-block");
                        }
                        if (errors.price) {
                            $("#price").addClass("is-invalid");
                            $("#error-price")
                                .text(errors.price[0])
                                .addClass("d-block");
                        }
                    },
                });
            });

            //Edit Product
            function editModal(e) {
                let id = e.getAttribute("data-id");
                save_method = "update";

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    type: "GET",
                    url: "products/" + id,
                    success: function (response) {
                        let result = response.data;
                        $("#name").val(result.name);
                        $("#description").val(result.description);
                        $("#price").val(result.price);
                        // $("#image").val(result.image);
                        $("#id").val(result.uuid);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // console.log(jqXHR.responseText);
                        // alert(jqXHR.responseText);
                        // Clear previous error messages
                        const errors = jqXHR.responseJSON.errors;
                        $(".invalid-feedback").text("");
                        $("input").removeClass("is-invalid");

                        // Display error messages
                        if (errors.name) {
                            $("#name").addClass("is-invalid");
                            $("#error-name")
                                .text(errors.name[0])
                                .addClass("d-block");
                        }
                        if (errors.description) {
                            $("#description").addClass("is-invalid");
                            $("#error-description")
                                .text(errors.description[0])
                                .addClass("d-block");
                        }
                        if (errors.price) {
                            $("#price").addClass("is-invalid");
                            $("#error-price")
                                .text(errors.price[0])
                                .addClass("d-block");
                        }
                    },
                });
                resetValidation();

                $("#productModal").modal("show");
                $(".modal-title").text("Edit Product");
                $(".btnSubmit").text("Save");
            }

            //Delete Product
            function deleteModal(e) {
                let id = e.getAttribute("data-id");
                Swal.fire({
                    title: "Delete Product?",
                    text: "Are you sure you want to delete this product?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            type: "DELETE",
                            url: "products/" + id,
                            dataType: "json",
                            success: function (response) {
                                $("#productModal").modal("hide");
                                $("#tableProduct").DataTable().ajax.reload();
                                Swal.fire({
                                    title: "Good job!",
                                    text: response.message,
                                    icon: "success",
                                    // showButtonText: false,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(jqXHR.responseText);
                                alert(jqXHR.responseText);
                            },
                        });
                    }
                });
            }
        </script>
    </body>
</html>
