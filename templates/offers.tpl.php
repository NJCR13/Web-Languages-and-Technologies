<?php function drawManageOffers(PDO $db, Array $listings) { ?>
    <div class="manage-offers">        
        <div class="offers-list">
            <?php if (empty($listings)) : ?>
                <p class="empty-message">No offers found.</p>
            <?php else : ?>
                <?php foreach ($listings as $listing) :
                    $orders = ServiceOrder::getOrdersForListing($db, $listing->id);
                    ?>
                        <?php foreach ($orders as $order) : 
                            $buyer = User::getUser($db, $order->buyer_id);
                            $listing = Listing::getListingById($db, $order->listing_id);
                        ?>
                            <div class="offer-card">
                                <div class="offer_image">
                                    <img src="https://images.pexels.com/videos/8197836/pexels-photo-8197836.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                                                width="100" alt="<?=$listing->id?>">
                                </div>
                                
                                <div class="offer-content">
                                    <div class="offer-details">
                                        <h1><?= htmlspecialchars($listing->title) ?></h1>
                                        <p>Client: <?= User::getUser($db, $order->buyer_id)->name ?></p>
                                        <p>Client Email: <?= User::getUser($db, $order->buyer_id)->email ?></p>
                                        <?php if(!is_null(User::getUser($db, $order->buyer_id)->phone)) : ?>
                                            <p>Client phone: <?= User::getUser($db, $order->buyer_id)->phone ?></p>
                                        <?php endif; ?>
                                        <p>Requested on: <?= date('M j, Y', strtotime($order->start_date)) ?></p>
                                        <p>Status: <?= htmlspecialchars($order->status) ?></p>
                                    </div>
                                    
                                    <?php if ($order->status === 'Pending') { ?>
                                        <div class="offer-actions">
                                            <form action="../actions/action_manage_offer.php" method="post" class="action-buttons">
                                                <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                                <button type="submit" name="action" value="accept" class="confirmbtn">
                                                    Accept Offer
                                                </button>
                                                <button type="submit" name="action" value="decline" class="cancelbtn">
                                                    Decline Offer
                                                </button>
                                            </form>
                                            <form class="message-button">
                                                <input type="hidden" name="receiver_id" value="<?= $order->buyer_id ?>">
                                                <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                                                <button formaction="../pages/chat.php" formmethod="post" type="submit">
                                                    Message
                                                </button>
                                            </form>
                                        </div>
                                    <?php } elseif($order->status === 'In Progress') { ?>
                                        <div class="offer-actions">
                                            <form action="../actions/action_manage_offer.php" method="post" class="action-buttons">
                                                <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                                <button type="submit" name="action" value="complete" class="confirmbtn">
                                                    Complete Order
                                                </button>
                                                <button type="submit" name="action" value="cancel" class="confirmbtn">
                                                    Cancel Order
                                                </button>
                                            </form>
                                            <form class="message-button">
                                                <input type="hidden" name="receiver_id" value="<?= $order->buyer_id ?>">
                                                <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                                                <button formaction="../pages/chat.php" formmethod="post" type="submit">
                                                    Message
                                                </button>
                                            </form>
                                        </div>
                                    <?php } else { ?>
                                        <div class="offer-actions">
                                            <form class="message-button">
                                                <input type="hidden" name="receiver_id" value="<?= $order->buyer_id ?>">
                                                <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                                                <button formaction="../pages/chat.php" formmethod="post" type="submit">
                                                    Message
                                                </button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>