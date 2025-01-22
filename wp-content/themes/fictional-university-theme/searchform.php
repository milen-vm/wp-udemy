<form class="search-form" action="<?php echo esc_url(site_url('/')); ?>" method="GET">
    <label class="headline headline--medium" for="s">Perform a new search:</label>
    <div class="search-form-row">
        <input class="s" id="s" type="search" name="s" placeholder="What are you looking for?">
        <!-- <button class="search-submit"  type="submit">Search</button> -->
        <input class="search-submit" type="button" value="Search">
    </div>
</form>