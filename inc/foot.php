<!-- Footer Start-->
<?php
if ($isAdminPage == false)
{
    $siteUrl = SITE_URL;
    $siteName = SITE_NAME;
    $currentYear = date('Y');
    $sponsorshipUrl = PAYSTACK_SPONSORSHIP_PAGE_URL;
    echo <<<EOQ
<div class="container-fluid bg-warning">
    <div class="row">
        <div class="col-md-12">
            <div class="m-3">
                <p class="text-center text-dark fw-bold">For sponsorships and support, visit <a style="color:#000;text-decoration:underline !important;" href="{$sponsorshipUrl}">this page</a></p>
            </div>
        </div>
    </div>
</div>

<footer class="footer mt-auto">
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-copyright-text">
                        <p class="mb-0">© {$currentYear}, <strong><a href="{$siteUrl}" class="text-white">{$siteName}</a></strong>. All rights reserved. Developed by <a href="https://wa.me/2349034770998" class="text-white"><strong>S-WEB</strong></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
EOQ;
}
?>
<!-- Footer End-->

<script src="assets/js/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/OwlCarousel/owl.carousel.js"></script>
<script src="assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="assets/vendor/mixitup/dist/mixitup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="assets/js/custom.js"></script>
<script src="assets/js/night-mode.js"></script>
<!-- CK EDITOR -->
<script src="assets/vendor/ckeditor5/ckeditor.js"></script>

<!-- To include the below only for admin pages -->
<?php
if ($isAdminPage)
{
    echo <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.0/dist/js/datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
EOQ;
}
?>

<?php
if (count($arAdditionalJsScripts) > 0)
{
  echo implode("\n", $arAdditionalJsScripts);
}
?>

<script>
var gSiteKey = '<?php echo DEF_GOOGLE_SITE_KEY;?>';
<?php
if (count($arAdditionalJs) > 0)
{
  echo implode("\n", $arAdditionalJs);
  echo "\n";
}
?>
</script>

<script>
$(document).ready(function() {
    <?php
    if (count($arAdditionalJsOnLoad) > 0)
    {
        echo implode("\n", $arAdditionalJsOnLoad);
    }
    ?>
    $('.btnLogoutSidebar').click(function()
    {
        doOpenLogoutModal();
    });
});
</script>

</body>
</html>