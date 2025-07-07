<div class="product-item">
    <form method="post">
        <input type="hidden" name="nama_warung" value="<?= $tls['nama_warung'] ?>">
        <input type="hidden" name="nama_makanan" value="<?= $tls['nama_makanan'] ?>">
        <input type="hidden" name="price" value="<?= $tls['price'] ?>">
        <input type="hidden" name="img" value="<?= $tls['img'] ?>">
        <figure>
            <img src="images/product/<?= $tls["img"] ?>" class="tab-image" alt="<?= $tls["nama_makanan"] ?>" width="250" name="img">
        </figure>
        <span name="nama_warung"><?= $tls["nama_warung"] ?></span>
        <h3 name="nama_makanan" class="product-title">
            <a href="#" name="nama_makanan" style="text-decoration: none;">
                <?= $tls["nama_makanan"] ?>
        </h3>
        </a>
        <span class="qty">1 Unit</span>
        <span class="rating">
            <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
            <?= $tls["rate"] ?>
        </span>
        <span class="price" name="price">RP <?= $tls["price"] ?></span>

        <div class="d-flex align-items-center justify-content-between">
            <input type="number" name="jumlah" value="1" min="1" class="form-control w-25">
            <!-- kirim data nama makanan, harga dan jumlah -->

            <button type="submit" class="btn btn-primary" name="cart">
                Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon>
            </button>
        </div>
    </form>
</div>