<footer id="site-footer" class="flex flex-col items-center bg-slate-800">
    <div class="py-4 px-4 flex flex-col items-center md:flex-row justify-between w-full bg-slate-700">
        <p>&copy; 2014-2025 Abhinav R</p>
        <ul class="flex flex-col items-center md:flex-row h-full [&>li>a]:flex [&>li>a]:h-full [&>li>a]:px-2 [&>li>a]:items-center [&>li>a:hover]:text-brand-red [&>li>a]:transition text-sm tracking-tighter">
            <li><a href="/about/">About</a></li>
            <li><a href="/contact/">Contact</a></li>
            <li><a href="/disclosure/">Disclosure</a></li>
            <li><a href="/disclaimer/">Disclaimer</a></li>
            <li><a href="/privacy-policy/">Privacy Policy</a></li>
            <li><a href="/terms-and-conditions/">Terms &amp; Conditions</a></li>
        </ul>
    </div>
</footer>
<?php if (!empty($props['load_jquery'])) : ?>
    <script src="<?= _asset_url_('/assets/jquery.min.js') ?>"></script>
<?php endif; ?>
<?php if (!empty($props['load_justified_gallery'])) : ?>
    <script src="<?= _asset_url_('/assets/jquery.justifiedGallery.min.js') ?>"></script>
    <script src="<?= _asset_url_("/assets/colorbox/jquery.colorbox-min.js") ?>"></script>
<?php endif; ?>
<script src="<?= _asset_url_('/assets/highlight/highlight-2.min.js') ?>"></script>
<script src="<?= _asset_url_("/assets/frontend.js") ?>"></script>
<script>hljs.highlightAll();</script>
</body>
</html>