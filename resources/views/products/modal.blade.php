<div
    class="modal fade"
    id="productModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form action="" id="productForm">
                    <input type="hidden" name="id" id="id" />
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                        />
                        <span class="invalid-feedback" id="error-name"></span>
                    </div>
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <input
                            type="text"
                            name="description"
                            id="description"
                            class="form-control"
                        />
                        <span
                            class="invalid-feedback"
                            id="error-description"
                        ></span>
                    </div>
                    <div class="mb-3">
                        <label for="Price">Price</label>
                        <input
                            type="number"
                            name="price"
                            id="price"
                            class="form-control"
                        />
                        <span class="invalid-feedback" id="error-price"></span>
                    </div>
                    <div class="mb-3">
                        <label for="image">Image (Max 2MB)</label>
                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control"
                            accept="image/jpg,image/jpeg,image/png,image/webp"
                        />
                        <span class="invalid-feedback" id="error-image"></span>
                    </div>
                    <div class="float-end">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btnSubmit">
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
