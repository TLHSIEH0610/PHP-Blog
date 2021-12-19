<?php if(! empty($post -> errors)):?>
    <ul>
        <?php foreach($post -> errors as $error) :?>
            <li><?php echo $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif;?>

<?php var_dump($category_ids); ?>

<form method="post">

        <div>
            <label for="title">Title</label>
            <!-- // -->
            <input name="title" id="title" value="<?php echo $post->title ;?>">
        </div>

        <div>
            <label for="content">Content</label>
            <input name="content" id="content" value="<?php echo $post->content ;?>">
        </div>

        <div>
            <label for="published_time">Published Time</label>
            <input name="published_time" id="published_time" value="<?php echo $post->published_time ;?>">
        </div>

        <fieldset>
            <legend>Categories</legend>
            
            <?php foreach($categories as $category): ?>
                <div>
                    <input type="checkbox" name="category[]" value="<?php echo $category['id'] ;?>" id="category<?php echo $category['id'] ?>" 
                    <?php if(in_array($category['id'], $category_ids)) : ?> checked <?php endif; ?> >
                    <label for="category<?php echo $category['id'] ?>"><?php echo $category['name'] ?></label>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <button>Save</button>


</form>