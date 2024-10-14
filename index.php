<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRIS Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
      input[type=number]::-webkit-outer-spin-button,
      input[type=number]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
      }
      input[type=number] {
      -moz-appearance: textfield;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="#">QRIS Converter</a>
      </div>
    </nav>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title">QRIS Dynamic Converter</h3>
            </div>
            <div class="card-body">
              <form id="qrisForm">
                <div class="mb-3">
                  <label for="qris" class="form-label">Input Data QRIS:</label>
                  <textarea class="form-control" name="qris" id="qris" rows="4" required placeholder="Paste your static QRIS data here..."></textarea>
                </div>
                <div class="mb-3">
                  <label for="qty" class="form-label">Input Nominal (Rp):</label>
                  <input type="text" class="form-control" name="qty" id="qty" required placeholder="Enter the amount...">
                </div>
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary btn-block">Convert</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qrModalLabel">Generated QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center" id="qrCodeResult">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <footer class="bg-dark text-white text-center py-3 mt-5">
      <p class="mb-0">QRIS Converter.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function formatNumberWithDots(value) {
          value = value.replace(/\D/g, '');
          return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }
      
      document.getElementById('qty').addEventListener('input', function (e) {
          let value = e.target.value;
          e.target.value = formatNumberWithDots(value);
      });
      
      $(document).ready(function() {
          $('#qrisForm').on('submit', function(e) {
              e.preventDefault();
              
              var qtyValue = $('#qty').val().replace(/\./g, ''); 
              
              var formData = $(this).serialize();
              formData = formData.replace($('#qty').val(), qtyValue);
      
              $.ajax({
                  type: 'POST',
                  url: 'convert.php',
                  data: formData,
                  success: function(response) {
                      $('#qrCodeResult').html(response);
      $('#qrModal').modal('show');
                  },
                  error: function() {
                      alert('Error generating QR code.');
                      console.error('Error during QR code generation');
                  }
              });
          });
      });
    </script>
  </body>
</html>
