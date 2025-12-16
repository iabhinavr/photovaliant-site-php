<article class="single-post-article" id="article-<?= $props['id'] ?>">
    <div class="content-hero bg-slate-700">
        <div class="h-full container px-2 mx-auto flex justify-center flex-col lg:max-w-3xl">
            <h1 class="text-3xl font-bold py-4 text-brand-green">
                <?= $props['title'] ?>
            </h1>
            <div><small>Published by Abhinav / Last updated:
                    <?= $props['modified_date'] ?>
                </small></div>
            <p class="py-1 my-3 pl-3 text-xl border-l-4 border-l-brand-yellow">
                <?= $props['excerpt'] ?>
            </p>
        </div>

    </div>
    <div class="post-content container mx-auto lg:max-w-3xl px-2 tracking-tight">
        <?= $props['content_html'] ?>
    </div>
    <div class="container-1">
        
            <?php if($props['gallery_data']['status'] === true) : ?>
            <div class="gallery-loader-initial">loading gallery images...</div>
            <ul id="my-gallery" style="display: none">
            <?php foreach ($props['gallery_data']['result']['image_list_json'] as $item_key => $gallery_item) : ?>
                <li>
                    <figure>
                        <a href="<?= $gallery_item['href'] ?>" title="<?= $gallery_item['caption'] ?>">
                            <img src="<?= $gallery_item['data_src'] ?>" alt="<?= $gallery_item['caption'] ?>" srcset="<?= $gallery_item['data_srcset'] ?>" sizes=" (min-width: 1280px) 25vw, (min-width: 800px) 33vw, 100vw" style="opacity: 0">
                        </a>
                        <figcaption><?= $gallery_item['caption'] ?></figcaption>
                    </figure>
                </li>
                <?php if ($item_key >= 14) break; ?>
            <?php endforeach; ?>
            </ul>
            <div class="gallery-loader" style="display: none">loading more images...</div>
            <div id="gallery-data" style="display: none"><?= $props['gallery_data']['result']['image_list'] ?></div>
            <?php else : ?>
                "gallery not found"
            <?php endif; ?>
    </div>
</article>
<section id="author-bio" class="my-4">
    <div class="container mx-auto lg:max-w-3xl">
        <div class="flex items-center p-6 rounded-md bg-slate-800">
            <!-- Image on the left -->
            <div class="w-40">
                <img src="https://cdn-2.coralnodes.com/coralnodes/uploads/thumbnail/<?= $props['author_bio']['featured_image'] ?>" alt="Profile Image" class="block rounded-full object-cover" loading="lazy">
            </div>

            <!-- Title and Description on the right -->
            <div class="ml-4">
                <h2 class="text-xl font-semibold">About <?= $props['author_bio']['title'] ?></h2>
                <p class="text-lg">
                    <?= $props['author_bio']['excerpt'] ?>
                </p>
            </div>
        </div>
    </div>
</section>

<?php if(!empty($props['related_content'])) : ?>
<section>
    <div class="container mx-auto lg:max-w-3xl">
        <h2 class="text-2xl font-bold py-2 pl-4 border-l-brand-yellow border-l-4 mb-4">Related articles:
        </h2>
        <ul class="grid p-4 gap-2 justify-center sm:grid-cols-3">
            
            <?php foreach ($props['related_content'] as $r): ?>
                <li class=" max-w-xs ">
                    <a href="/<?= $r['slug'] ?>/" class="hover:text-brand-yellow">
                        <img class="block rounded-md"
                            src="https://cdn-2.coralnodes.com/coralnodes/uploads/medium/<?= $r['featured_image'] ?>"
                            alt="<?= $r['title'] ?>" loading="lazy">
                        <span class="block text-sm py-2 font-bold">
                            <?= $r['title'] ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?> 
        </ul>
    </div>

</section>
<?php endif; ?>