<?php
session_start();
define("freshcery", "http://freshcery");
include '../configration/db.config.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles/style.css" rel="stylesheet">
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
</head>


<?php
$query = $pdo->prepare("SELECT * FROM products ");
$query->execute();
$products = $query->fetchAll(PDO::FETCH_OBJ);
$count = $pdo->prepare("SELECT count(*) FROM products");
$count->execute();
$_SESSION['product_count'] = $count->fetchColumn();


$stmt = $pdo->query("SELECT id, name FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<body>
  <div id="wrapper">
    <?php include 'nav.php' ?>
    <?php
    if (isset($_GET['message'])) {
      echo "<h3>" . $_GET['message'] . "</h3>";
    }
    ?>
    <div style="display: flex; flex-direction:column; justify-content:center; align-items: center;">
      <h1 class="display-3 fw-semibold text-center">PRODUCTS</h1>
      <button class="btn btn-primary mb-4 text-center" data-toggle="modal" data-target="#exampleModal">ADD Products</button>
    </div>

    <div class="container-fluid">
      <table class="table">
        <thead>
          <tr>
            <th>product-ID</th>
            <th>product-image</th>
            <th>product-name</th>
            <th>price</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
              <tr>
                <td><?= $product->id; ?></td>
                <td><img src="<?= freshcery; ?>/assets/img/<?= htmlspecialchars($product->image); ?>" height="100px" width="100px"></td>
                <td><?= $product->title; ?></td>
                <td><?= $product->price; ?></td>
                <td><?= $product->quantity; ?></td>
                <td>
                  <button class="btn btn-warning text-white update-btn"
                    data-id="<?= $product->id; ?>"
                    data-title="<?= $product->title; ?>"
                    data-description="<?= $product->description; ?>"
                    data-price="<?= $product->price; ?>"
                    data-quantity="<?= $product->quantity; ?>"
                    data-image="<?= $product->image; ?>"
                    data-exp_date="<?= $product->exp_date; ?>"
                    data-category_id="<?= $product->category_id; ?>"
                    data-toggle="modal"
                    data-target="#updateModal">Update</button>
                  <form action="../admin-panel/include/product.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?= $product->id ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
                </td>

              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="alert alert-danger bg-danger text-white text-centre">
              <h1>error!</h1>
            </div>
          <?php endif; ?>
        </tbody>
      </table>



    </div>
  </div>
  <form action="../admin-panel/include/product.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Product</h5>
          </div>
          <div class="modal-body">
            <input type="text" name="title" id="title" placeholder="Title" class="form-control">
            <span id="title-error" class="text-danger small"></span>
            <br>
            <input type="text" name="description" id="description" placeholder="Description" class="form-control">
            <span id="description-error" class="text-danger small"></span>
            <br>
            <input type="text" name="price" id="price" placeholder="Price" class="form-control">
            <span id="price-error" class="text-danger small"></span>
            <br>
            <input type="number" name="quantity" id="quantity" placeholder="Quantity" class="form-control">
            <span id="quantity-error" class="text-danger small"></span>
            <br>
            <input type="file" name="image" id="image" class="form-control">
            <span id="image-error" class="text-danger small"></span>
            <br>
            <input type="date" name="exp_date" id="exp_date" class="form-control">
            <span id="exp_date-error" class="text-danger small"></span>
            <br>
            <select name="category_id" id="category_id" class="form-control">
              <option value="">-- Select Category --</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?= $category->id ?>">
                  <?= htmlspecialchars($category->name) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <span id="category_id-error" class="text-danger small"></span>

            <br>
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-success" name="action" value="add">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form action="../admin-panel/include/product.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="action" value="update">
    <input type="hidden" name="id" id="update_id">
    <input type="hidden" name="current_image" id="update_current_image">
    <div class="modal fade" id="updateModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Product</h5>
          </div>
          <div class="modal-body">


            <input type="text" name="title" id="update_title" class="form-control">
            <span id="title-error" class="text-danger small"></span>
            <label id="title-error"></label>
            <br>
            <input type="text" name="description" id="update_description" class="form-control">
            <span id="description-error" class="text-danger small"></span>
            <br>
            <input type="text" name="price" id="update_price" class="form-control">
            <span id="price-error" class="text-danger small"></span>
            <br>
            <input type="number" name="quantity" id="update_quantity" class="form-control">
            <span id="quantity-error" class="text-danger small"></span>
            <br>
            <input type="file" name="image" class="form-control">
            <span id="image-error" class="text-danger small"></span>
            <br>
            <input type="date" name="exp_date" id="update_exp_date" class="form-control">
            <span id="exp_date-error" class="text-danger small"></span>
            <br>
            <select name="category_id" id="update_category_id" class="form-control">
              <option value="">-- Select Category --</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?= $category->id ?>">
                  <?= htmlspecialchars($category->name) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <span id="category_id-error" class="text-danger small"></span>

            <br>
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-success update-btn" value="Update">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Title Validation
      const title = document.getElementById("title");
      title.focus();
      title.addEventListener("blur", function() {
        const error = document.getElementById("title-error");
        error.textContent = this.value.trim() === "" ? "Title is required." : "";
      });

      // Description Validation
      const description = document.getElementById("description");
      description.addEventListener("blur", function() {
        const error = document.getElementById("description-error");
        error.textContent = this.value.trim() === "" ? "Description is required." : "";
      });

      // Price Validation (Must be a number greater than 0)
      // Price Validation (Format: number/unit like 45.99/kg)
      const price = document.getElementById("price");
      price.addEventListener("blur", function() {
        const error = document.getElementById("price-error");
        const pricePattern = /^\d+(?:\.\d+)?\/(kg|unit)$/i;
        if (this.value.trim() === "") {
          error.textContent = "Price is required.";
        } else if (!pricePattern.test(this.value.trim())) {
          error.textContent = "Price must be in format like 45.99/kg, or 20/unit.";
        } else {
          error.textContent = "";
        }
      });


      // Quantity Validation (Must be a positive integer)
      const quantity = document.getElementById("quantity");
      quantity.addEventListener("blur", function() {
        const error = document.getElementById("quantity-error");
        if (this.value.trim() === "") {
          error.textContent = "Quantity is required.";
        } else if (!Number.isInteger(Number(this.value)) || this.value <= 0) {
          error.textContent = "Quantity must be a positive integer.";
        } else {
          error.textContent = "";
        }
      });

      // Image Validation (Only JPG, JPEG, PNG)
      const image = document.getElementById("image");
      image.addEventListener("change", function() {
        const error = document.getElementById("image-error");
        const file = this.files[0];
        if (!file) {
          error.textContent = "Image is required.";
          return;
        }
        const validExtensions = ["image/jpeg", "image/png", "image/jpg"];
        if (!validExtensions.includes(file.type)) {
          error.textContent = "Only JPG, JPEG, and PNG formats are allowed.";
        } else {
          error.textContent = "";
        }
      });

      // Expiry Date Validation (Must not be empty and should be a future date)
      const expDate = document.getElementById("exp_date");
      expDate.addEventListener("blur", function() {
        const error = document.getElementById("exp_date-error");
        if (this.value === "") {
          error.textContent = "Expiry date is required.";
        } else if (new Date(this.value) <= new Date()) {
          error.textContent = "Expiry date must be a future date.";
        } else {
          error.textContent = "";
        }
      });

      // Category ID Validation (Must be a positive number)
      const categoryId = document.getElementById("category_id");
      categoryId.addEventListener("blur", function() {
        const error = document.getElementById("category_id-error");
        if (this.value.trim() === "") {
          error.textContent = "Category ID is required.";
        } else if (isNaN(this.value) || parseInt(this.value) <= 0) {
          error.textContent = "Category ID must be a positive number.";
        } else {
          error.textContent = "";
        }
      });
    });

    const updateTitle = document.getElementById('update_title');
    const updateDesc = document.getElementById('update_description');
    const updatePrice = document.getElementById('update_price');
    const updateQty = document.getElementById('update_quantity');
    const updateExp = document.getElementById('update_exp_date');
    const updateCat = document.getElementById('update_category_id');

    const titleErr2 = document.createElement('div');
    const descErr2 = document.createElement('div');
    const priceErr2 = document.createElement('div');
    const qtyErr2 = document.createElement('div');
    const expErr2 = document.createElement('div');
    const catErr2 = document.createElement('div');

    // Style all error elements
    [titleErr2, descErr2, priceErr2, qtyErr2, expErr2, catErr2].forEach(err => {
      err.style.color = 'red';
      err.style.fontSize = '14px';
    });

    updateTitle.parentNode.insertBefore(titleErr2, updateTitle.nextSibling);
    updateDesc.parentNode.insertBefore(descErr2, updateDesc.nextSibling);
    updatePrice.parentNode.insertBefore(priceErr2, updatePrice.nextSibling);
    updateQty.parentNode.insertBefore(qtyErr2, updateQty.nextSibling);
    updateExp.parentNode.insertBefore(expErr2, updateExp.nextSibling);
    updateCat.parentNode.insertBefore(catErr2, updateCat.nextSibling);

    const pricePattern2 = /^\d+(?:\.\d+)?\/(kg|gm|unit)$/i;

    // Blur validations
    updateTitle.addEventListener('blur', () => {
      titleErr2.textContent = updateTitle.value.trim() === '' ? "Title is required." : '';
    });

    updateDesc.addEventListener('blur', () => {
      descErr2.textContent = updateDesc.value.trim() === '' ? "Description is required." : '';
    });

    updatePrice.addEventListener('blur', () => {
      const val = updatePrice.value.trim();
      if (val === '') {
        priceErr2.textContent = "Price is required.";
      } else if (!pricePattern2.test(val)) {
        priceErr2.textContent = "Enter valid price format: e.g., 35/kg, 500/gm, or 10/unit.";
      } else {
        priceErr2.textContent = "";
      }
    });

    updateQty.addEventListener('blur', () => {
      qtyErr2.textContent = updateQty.value.trim() === '' ? "Quantity is required." : '';
    });

    updateExp.addEventListener('blur', () => {
      expErr2.textContent = updateExp.value.trim() === '' ? "Expiry date is required." : '';
    });

    updateCat.addEventListener('blur', () => {
      catErr2.textContent = updateCat.value.trim() === '' ? "Category ID is required." : '';
    });

    // Form submit validation
    document.querySelector('form[action*="product.inc.php"]').addEventListener('submit', function(e) {
      let valid = true;

      if (updateTitle.value.trim() === '') {
        titleErr2.textContent = "Title is required.";
        valid = false;
      }

      if (updateDesc.value.trim() === '') {
        descErr2.textContent = "Description is required.";
        valid = false;
      }

      const priceVal = updatePrice.value.trim();
      if (priceVal === '') {
        priceErr2.textContent = "Price is required.";
        valid = false;
      } else if (!pricePattern2.test(priceVal)) {
        priceErr2.textContent = "Enter valid price format: e.g., 35/kg, 500/gm, or 10/unit.";
        valid = false;
      }

      if (updateQty.value.trim() === '') {
        qtyErr2.textContent = "Quantity is required.";
        valid = false;
      }

      if (updateExp.value.trim() === '') {
        expErr2.textContent = "Expiry date is required.";
        valid = false;
      }

      if (updateCat.value.trim() === '') {
        catErr2.textContent = "Category ID is required.";
        valid = false;
      }

      if (!valid) {
        e.preventDefault(); // Prevent submission
      }
    });
  </script>


  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


  <script>
    $(document).ready(function() {

      $(".update-btn").click(function() {
        let id = $(this).data("id");
        let title = $(this).data("title");
        let description = $(this).data("description");
        let price = $(this).data("price");
        let quantity = $(this).data("quantity");
        let image = $(this).data("image");
        let exp_date = $(this).data("exp_date");
        let category_id = $(this).data("category_id");

        $("#update_id").val(id);
        $("#update_title").val(title);
        $("#update_description").val(description);
        $("#update_price").val(price);
        $("#update_quantity").val(quantity);
        $("#update_exp_date").val(exp_date);
        $("#update_category_id").val(category_id);
        $("#update_current_image").val(image);

        // Show modal
        $("#updateModal").modal("show");
      });

      // ✅ Bind validations after modal is shown
      $("#updateModal").on("shown.bs.modal", function() {
        // Title

        $("#update_title").off("blur").on("blur", function() {
          let error = $("#title-error");
          error.text($('#update_title').val().trim() === "" ? "" : "Title is required.");
        });


        // Description
        $("#update_description").off("blur").on("blur", function() {
          let error = $("#description-error");
          error.text($(this).val().trim() ? "" : "Description is required.");
        });

        // Price
        $("#update_price").off("blur").on("blur", function() {
          let val = $(this).val().trim();
          let pattern = /^\d+(\.\d+)?\/(kg|gm|unit)$/i;
          let error = $("#price-error");
          if (val === "") {
            error.text("Price is required.");
          } else if (!pattern.test(val)) {
            error.text("Format must be like 25.99/kg, 100/gm, 5/unit.");
          } else {
            error.text("");
          }
        });

        // Quantity
        $("#update_quantity").off("blur").on("blur", function() {
          let val = $(this).val().trim();
          let error = $("#quantity-error");
          error.text(val === "" || parseInt(val) <= 0 ? "" : "Quantity must be greater than 0.");
        });

        // Expiry Date
        $("#update_exp_date").off("blur").on("blur", function() {
          let val = $(this).val();
          let error = $("#exp_date-error");
          let selected = new Date(val);
          let today = new Date();
          today.setHours(0, 0, 0, 0);
          if (val === "") {
            error.text("Date is required.");
          } else if (selected < today) {
            error.text("Date must be today or in the future.");
          } else {
            error.text("");
          }
        });

        // Category ID
        $("#update_category_id").off("blur").on("blur", function() {
          let val = $(this).val().trim();
          let error = $("#category_id-error");
          error.text(parseInt(val) > 0 ? "" : "Category ID must be a positive number.");

        });

        // Image (change)
        $("#updateModal input[name='image']").off("change").on("change", function() {
          let error = $("#image-error");
          let file = this.value.toLowerCase();
          let ext = file.split('.').pop();
          if (this.files.length > 0 && !["jpg", "jpeg", "png"].includes(ext)) {
            error.text("Only JPG, JPEG, PNG allowed.");
            this.value = "";
          } else {
            error.text("");
          }
        });
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.table').DataTable({
        "pageLength": 4, // Show only 4 entries per page
        "lengthMenu": [4, 8, 12, 16], // Allow user to change entries per page
        "ordering": false // (Optional) Disable sorting if not needed
      });
    });
  </script>
  <script type="text/javascript">

  </script>
</body>

</html>