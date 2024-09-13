<footer>
<!-- Your custom scripts -->
<script src="<?=BASE_URL?>dist/assets/js/vendor.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/js/app.js"></script>

<!-- Plugins js -->
<script src="<?=BASE_URL?>dist/assets/libs/dropzone/min/dropzone.min.js"></script>

<!-- Demo js-->
<script src="<?=BASE_URL?>dist/assets/js/pages/form-fileuploads.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/jquery/jquery.min.js"></script>
 
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/libs/pdfmake/build/vfs_fonts.js"></script>

<!-- SweetAlert JS -->
<script src="<?=BASE_URL?>dist/assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="<?=BASE_URL?>dist/assets/js/pages/sweet-alerts.js"></script>
<script src="<?=BASE_URL?>dist/assets/js/pages/datatables.js"></script>

<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function formatPhoneNumberInput(selector) {
        $(document).ready(function() {
            // Apply formatting to all inputs with the specified selector
            $(selector).each(function() {
                var input = $(this);
                
                // Initialize the phone input with the default +971 prefix
                input.val(function(index, currentValue) {
                    return formatPhoneNumber(currentValue);
                });

                // Handle paste event
                input.bind('paste', function(e) {
                    e.preventDefault();
                    var inputValue = e.originalEvent && e.originalEvent.clipboardData.getData('Text');
                    inputValue = inputValue.replace(/\D/g, '');
                    if (!$.isNumeric(inputValue)) {
                        return false;
                    } else {
                        // Format to +971 XXX XXX XXXX
                        var formattedValue = formatPhoneNumber(inputValue);
                        input.val(formattedValue);
                    }
                });

                // Handle input event for typing
                input.on('input', function(e) {
                    // Get current value and remove non-numeric characters except the starting +971
                    var curval = input.val().replace(/\D/g, '');
                    
                    // Ensure it starts with +971
                    if (curval.length > 0 && !curval.startsWith('971')) {
                        curval = '971' + curval;
                    }
                    
                    var formattedValue = formatPhoneNumber(curval);
                    input.val(formattedValue);
                });
            });

            function formatPhoneNumber(value) {
                // Remove all non-numeric characters except leading +
                value = value.replace(/\D/g, '');

                // Ensure it starts with '971' for UAE country code
                if (value.length === 0) {
                    return '+971 ';
                }
                
                if (!value.startsWith('971')) {
                    value = '971' + value;
                }

                // Format value as +971 XXX XXX XXXX
                var formattedValue = '+971 ';
                if (value.length > 3) {
                    formattedValue += value.substring(3, 6) + ' '; // First 3 digits (area/operator code)
                }
                if (value.length > 6) {
                    formattedValue += value.substring(6, 9) + ' '; // Next 3 digits
                }
                if (value.length > 9) {
                    formattedValue += value.substring(9, 12); // Last 3 digits
                }

                return formattedValue;
            }
        });
    }

    function formatEmiratesIDInput(selector) {
        $(document).ready(function() {
            // Apply formatting to all inputs with the specified selector
            $(selector).each(function() {
                var input = $(this);

                // Handle input event for typing
                input.on('input', function(e) {
                    // Get current value and remove non-numeric characters
                    var curval = input.val().replace(/\D/g, '');

                    // Format value as 784-YYYY-NNNNNNN-C
                    var formattedValue = formatEmiratesID(curval);
                    input.val(formattedValue);
                });

                // Handle paste event
                input.bind('paste', function(e) {
                    e.preventDefault();
                    var inputValue = e.originalEvent && e.originalEvent.clipboardData.getData('Text');
                    inputValue = inputValue.replace(/\D/g, '');
                    var formattedValue = formatEmiratesID(inputValue);
                    input.val(formattedValue);
                });
            });

            function formatEmiratesID(value) {
                // Ensure that the first three digits are 784 for UAE
                if (value.length === 0) {
                    return '784-';
                }

                if (!value.startsWith('784')) {
                    value = '784' + value;
                }

                // Format value as 784-YYYY-NNNNNNN-C
                var formattedValue = '784';
                if (value.length > 3) {
                    formattedValue += '-' + value.substring(3, 7); // Year (YYYY)
                }
                if (value.length > 7) {
                    formattedValue += '-' + value.substring(7, 14); // Unique number (NNNNNNN)
                }
                if (value.length > 14) {
                    formattedValue += '-' + value.substring(14, 15); // Check digit (C)
                }

                return formattedValue;
            }
        });
    }

    function formatPassportInput(selector) {
        $(document).ready(function() {
            // Apply formatting to all inputs with the specified selector
            $(selector).each(function() {
                var input = $(this);

                // Handle input event for typing
                input.on('input', function(e) {
                    // Get current value and remove all non-alphanumeric characters
                    var curval = input.val().replace(/[^a-zA-Z0-9]/g, '');

                    // Format value as A12345678 (1 letter followed by 8 digits)
                    var formattedValue = formatPassportNumber(curval);
                    input.val(formattedValue);
                });

                // Handle paste event
                input.bind('paste', function(e) {
                    e.preventDefault();
                    var inputValue = e.originalEvent && e.originalEvent.clipboardData.getData('Text');
                    inputValue = inputValue.replace(/[^a-zA-Z0-9]/g, ''); // Remove non-alphanumeric characters
                    var formattedValue = formatPassportNumber(inputValue);
                    input.val(formattedValue);
                });
            });

            function formatPassportNumber(value) {
                // Ensure that the value is not longer than 9 alphanumeric characters
                if (value.length > 9) {
                    value = value.substring(0, 9);
                }

                // Return the formatted passport number (just the alphanumeric string)
                return value.toUpperCase(); // Convert letters to uppercase for consistency
            }
        });
    }

    // Apply formatting functions to input fields
    formatPhoneNumberInput(".phone-input");
    formatEmiratesIDInput(".emirates-id-input");
    formatPassportInput(".passport-input");

    // Add form submission handler
    $('form').on('submit', function(e) {
        // Clear empty or unchanged phone input
        $('.phone-input').each(function() {
            var input = $(this);
            if (input.val() === '+971 ') {
                input.val(''); // Clear it to prevent sending default value
            }
        });

        // Clear empty or unchanged Emirates ID input
        $('.emirates-id-input').each(function() {
            var input = $(this);
            if (input.val() === '784-') {
                input.val(''); // Clear it to prevent sending default value
            }
        });

        // Clear empty passport input
        $('.passport-input').each(function() {
            var input = $(this);
            if (input.val().length === 0) {
                input.val(''); // Clear it to prevent sending empty value
            }
        });
    });
</script>


</footer>

</body>
</html>