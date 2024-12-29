<?= loadPartials('head') ?>
<?= loadPartials('header') ?>
<?= loadPartials('showcase-search') ?>
<section>
    <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $status ?> Error</div>
        <p class="text-center text-2xl mb-4">
            <?= $message ?>
        </p>

        <a href="/listings"
            class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
            Go Back to listings
        </a>
    </div>
</section>

<?php loadPartials('footer'); ?>