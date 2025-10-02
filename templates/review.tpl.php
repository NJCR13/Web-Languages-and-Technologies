<?php declare(strict_types = 1); 

// require_once(__DIR__ . '/../includes/session.php');
?>

<?php function drawReviewForm(Listing $listing, User $provider, Order $order) { ?>
    <div class="leave_review">
        <h2>Leave a Review</h2>
        <form id="review-form" action="../actions/action_leave_review.php" method="post">
            <p id="rating-error" style="color: red; display: none;">Please select a rating before submitting.</p>
            <input type="hidden" name="provider_id" value="<?= $provider->id ?>">
            <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
            <input type="hidden" name="order_id" value="<?= $order->id ?>">

            <div class="star_rating_container">
                <label>Rating:</label>
                <div class="star_rating">
                    <input type="radio" name="rating" value="5" id="5"><label for="5">★</label>
                    <input type="radio" name="rating" value="4" id="4"><label for="4">★</label>
                    <input type="radio" name="rating" value="3" id="3"><label for="3">★</label>
                    <input type="radio" name="rating" value="2" id="2"><label for="2">★</label>
                    <input type="radio" name="rating" value="1" id="1"><label for="1">★</label>
                </div>
            </div>

            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" rows="4" placeholder="Leave any comment here"></textarea>

            <div class="leave_review_button">
                <button type="submit">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
<?php } ?>

<?php function drawReviews(array $reviews) { ?>
    <div class="reviews_title">
        <h1>Reviews:</h1>
    </div>
    <?php foreach ($reviews as $review) { ?>
        <div class="reviews">
            <h4><?= htmlspecialchars($review->buyer_name) ?></h4>
            <div class="star_ratings">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?= $i <= $review->rating ? 'filled' : 'empty' ?>">★</span>
                <?php endfor; ?>
            </div>
            <p><?= htmlspecialchars($review->comment) ?></p>
        </div>
    <?php } ?>
<?php } ?>