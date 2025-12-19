<section id="home-intro" class="single-post-article">
    <div class="post-content container flex flex-wrap items-center mx-auto lg:max-w-4xl px-2 tracking-tight mt-6">
        <div class="intro-profile-img basis-full mx-[15vw] sm:mx-4 sm:basis-1/3 lg:basis-1/4 grow-0 shrink">
            <img src="<?= _asset_url_("/assets/images/intro-pic-abhinav-r.jpg") ?>" alt="Abhinav R - nature photographer">
        </div>
        <div class="intro-description py-4 flex-1">
            <?= $props['intro_content_html'] ?>
        </div>
    </div>

     <div class="post-content container mx-auto lg:max-w-3xl px-2 tracking-tight mt-6">
        <div class="py-4">
            <?= $props['intro_content_html_part_2'] ?>
        </div>
    </div>
</section>

<section id="galleries">
    <h2 class="text-xl text-center py-8">Galleries</h2>
    <ul class="container mx-auto lg:max-w-4xl grid gap-4 sm:grid-cols-3">
    <?php foreach($props['galleries'] as $gallery) : ?>
        <li class="sm:col-span-1 mb-4">

            <?php if (!empty($gallery['featured_image'])) : ?>
            <div class="relative">
                <a href="/galleries/<?= $gallery['slug'] ?>/">
                <img src="https://cdn-2.photovaliant.com/photovaliant/uploads/<?= $gallery['featured_image'] ?>" loading="lazy" alt="<?= $gallery['title'] ?>" class="rounded-t-xl w-full h-full object-cover" width="800" height="450">
                </a>
                <small class="absolute right-0 bottom-0 bg-slate-800 text-white px-2 py-1 text-sm">updated: <?= $gallery['modified_date'] ?></small>
            </div>
            <?php endif; ?>

            <div class="p-4 space-y-2">
                <h2>
                    <a href="/<?= $gallery['slug'] ?>/" class="text-lg text-gray-100 hover:text-brand-yellow"><?= $gallery['title'] ?></a>
                </h2>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
</section>

<section id="home-faq" class="home-faq-section bg-slate-800">
    <div class="container mx-auto lg:max-w-3xl px-2 tracking-tight mt-6">
        <h2 class="text-2xl text-center pt-8 text-brand-yellow">F-I-A-Q <br>(Frequently &amp; Infrequently Asked Questions)</h2>
        <div class="py-8 text-lg">
            <?= $props['faq_desc_content_html'] ?>
        </div>
        <div class="py-8">
            <?= $props['faq_content_html'] ?>
        </div>
    </div>
</section>