<?php declare(strict_types = 1); ?>

<?php function drawListings(string $listingPageTitle, array $listings, ?string $currentSearchTerm = null, ?string $currentSortOption = null) { ?>

    <div class="listings-content">
        <aside class="sidebar-filter">
            <h3>Filters</h3>
            <!-- Static Filters For Now -->
            <ul>
                <li><input type="checkbox"> Category 1 </li>
                <li><input type="checkbox"> Category 2 </li>
                <li><input type="checkbox"> Category 3 </li>
            </ul>
            <p><?= count($listings) ?> result(s) found.</p>
        </aside>

        <section class="content-listings">
            <h1><?= htmlspecialchars($listingPageTitle) ?></h1>

            <div class="search-sort-bar" style="margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-radius: 5px;">
                <form action="listings.php" method="GET" class="sort-form" style="display: inline-block;">
                    <?php // If there's an active search term, include it as a hidden field
                          // so that sorting doesn't lose the search filter. ?>
                    <?php if ($currentSearchTerm !== null && $currentSearchTerm !== ''): ?>
                        <input type="hidden" name="search_query" value="<?= htmlspecialchars($currentSearchTerm) ?>">
                    <?php endif; ?>

                    <label for="sort-dropdown" style="margin-right: 5px;">Sort By:</label>
                    <select name="sort_by" id="sort-dropdown" class="sort-dropdown-listings" onchange="this.form.submit()" style="padding: 5px; border-radius: 3px;">
                        <?php
                            $sortOptionsAvailable = [
                                'date_desc' => 'Newest First',
                                'date_asc' => 'Oldest First',
                                'price_asc' => 'Price: Low to High',
                                'price_desc' => 'Price: High to Low',
                                'title_asc' => 'Title: A-Z',
                                'title_desc' => 'Title: Z-A'
                            ];

                            $activeSort = $currentSortOption ?? 'date_desc';

                            foreach ($sortOptionsAvailable as $value => $text): ?>
                                <option value="<?= $value ?>" <?= ($activeSort === $value) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($text) ?>
                                </option>
                            <?php endforeach; ?>
                    </select>
                    <noscript><button type="submit" style="padding: 5px 10px; margin-left: 5px;">Sort</button></noscript>
                </form>
            </div>

            <div class="card-grid-listings">
                <?php if (empty($listings)): ?>
                    <p>No listings found matching your criteria.</p>
                <?php else: ?>
                    <?php foreach ($listings as $listing): ?>
                        <div class="listing-card" style="position: relative; border: 1px solid #ddd; margin-bottom: 15px; padding: 10px; border-radius: 5px; background-color: white; cursor: pointer;">
                            <a href="listing.php?listing_id=<?= htmlspecialchars((string)$listing->id) ?>"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"
                            aria-label="View details for <?= htmlspecialchars($listing->title) ?>">
                                <?/* This invisible link overlay makes the whole card clickable */?>
                            </a>
                            <?php
                                $imageBasePath = '/images/';
                                $commonListingImageFilename = 'listings.jpg';
                            ?>
                            <img src="<?= $listing->getFirstImageURL($imageBasePath, $commonListingImageFilename) ?>" alt="<?= htmlspecialchars($listing->title) ?>" style="width: 100%; max-width: 250px; height: auto; margin-bottom: 10px; border-radius: 3px;">
                            <h4 class="listing-title" style="margin-top: 0; margin-bottom: 5px;"><?= htmlspecialchars($listing->title) ?></h4>
                            <p class="listing-category" style="font-size: 0.9em; color: #555; margin-bottom: 5px;">Category: <?= htmlspecialchars($listing->category) ?></p>
                            <p class="listing-price" style="font-weight: bold; margin-bottom: 0;">Price: $<?= htmlspecialchars(number_format($listing->price, 2)) ?></p>
                            <?php
                            ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
<?php } ?>

<?php function drawListing(Listing $listing, User $user, ?Order $order) { ?>
    <div class="listing">
        <div class="listing_left_side">
            <h4><?= $listing->title ?></h4>
            <img src="https://images.pexels.com/videos/8197836/pexels-photo-8197836.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                width="800" alt="Image not Found">
            <p><?= $listing->description ?></p>
        </div>

        <div class="listing_right_side">
            <div class="profile">
                <img src="https://freepngimg.com/svg/image/abstract/172222-user-s-profile-icon.svg" width="75"
                    alt="Profile Picture">
                <div class="profile_text">
                    <p><span class=user_name><?= $user->name ?></span></p>
                    <p><span class="label">Email:</span><span class=user_email><?= $user->email ?></span></p>
                    <p><span class="label">Phone:</span><span class=user_phone><?= $user->phone ?></span></p>
                    <p><span class="label">Rating:</span><span class=user_rating><?= $user->rating ?></span></p>
                </div>
            </div>
    
            <div class="price">
                <?php if($order->buyer_id == $_SESSION['user_id'] && isset($_SESSION['user_id'])) { ?>
                    <p>Start Date: <?= $order->start_date ?></p>
                    <p>State: <?= $order->status ?></p>
                <?php } else { ?>
                    <p>Pricing:</p>
                    <p>Starting at <?= $listing->price ?> &euro;</p>
                <?php } ?>

                <?php if ($listing->provider_id == $_SESSION['user_id']) { ?>
                    <div class="view_clients_button">
                        <button onclick="window.location.href='manage_offers.php?>'" type="button" class="confirmbtn">
                            View Clients
                        </button>
                    </div>
                <?php } elseif (($order->provider_id != $_SESSION['user_id'] && $order->buyer_id != $_SESSION['user_id']) || !isset($_SESSION['user_id'])) { ?>
                    <div class="hire_button">
                        <form>
                            <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                            <button formaction="../pages/checkout.php" formmethod="get" type="submit">
                                Hire
                            </button>
                        </form>
                    </div>
                    <div class="message_button">
                        <form>
                            <input type="hidden" name="receiver_id" value="<?= $listing->provider_id ?>">
                            <input type="hidden" name="listing_id" value="<?= $listing->id ?>">                            
                            <button formaction="../pages/chat.php" formmethod="post" type="submit">
                                Message
                            </button>
                        </form>
                    </div>
                <?php } elseif ($order->buyer_id == $_SESSION['user_id']) { ?>
                    <div class="review_button">
                        <form>
                            <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                            <button formaction="../pages/review.php" formmethod="get" type="submit">
                                Review
                            </button>
                        </form>
                    </div>
                    <div class="message_button">
                        <form>
                            <input type="hidden" name="receiver_id" value="<?= $listing->provider_id ?>">
                            <input type="hidden" name="listing_id" value="<?= $listing->id ?>">                            
                            <button formaction="../pages/chat.php" formmethod="post" type="submit">
                                Message
                            </button>
                        </form>
                    </div>
                <?php } ?>


            </div>
        </div>
    </div>

    <?php if ($listing->provider_id == $_SESSION['user_id']) { ?>
        <div class="edit_listing">
            <div class="edit_button">
                <form>
                    <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                    <button formaction="../pages/edit_listing.php" formmethod="get" type="submit">
                        Edit
                    </button>
                </form>
            </div>
        </div>
    <?php } ?>
<?php } ?>


<!-- both this and categories and most that draw a slider of multiple listings should be the same draw function but with different variables introduced, by getting with the help of actions -->

<?php function drawCategoriesSlider(array $listings) { ?>
        <section id="top_categories">
            <div class="top_header">
                <h1>Top Categories:</h1>
            
                <div class="top_see_more">
                    <form>
                        <button formaction="../pages/listings.php" formmethod="get" type="submit">
                            See More
                        </button>
                    </form>
                </div>
            </div>

            <div class="top_controls">
                <div class="top_slider prev">
                    <button class="slider-nav prev-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/271/271220.png" width="25" alt="Previous">
                    </button>
                </div>

                <div class="top_button">
                    <?php foreach($listings as $listing) { ?>
                        <form>
                            <button type="submit">
                                <img src="https://images.pexels.com/videos/8197836/pexels-photo-8197836.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                                    width="150" alt="Alt image">
                                <p><?=$listing['category']?></p>
                            </button>
                        </form>
                    <?php } ?>
                </div>

                <div class="top_slider next">
                    <button class="slider-nav next-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/271/271228.png" width="25" alt="Next">
                    </button>
                </div>
            </div>        
        </section>
<?php } ?>

<?php function drawListingsSlider(array $listings) { ?> 
        <section id="top_listings">
            <div class="top_header">
                <h1>Top Picks:</h1>
            
                <div class="top_see_more">
                    <form>
                        <button formaction="../pages/listings.php" formmethod="get" type="submit">
                            See More
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="top_controls">
                <div class="top_slider prev">
                    <button class="slider-nav prev-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/271/271220.png" width="25" alt="Previous">
                    </button>
                </div>

                <div class="top_button">
                    <?php foreach($listings as $listing) { ?>
                    <form>
                        <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                        <button formaction="../pages/listing.php" formmethod="get" type="submit">
                            <img src="https://images.pexels.com/videos/8197836/pexels-photo-8197836.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                                width="150" alt="<?=$listing->id?>">
                            <p><?=$listing->title?></p>
                        </button>
                    </form>
                    <?php } ?>
                </div>
        
                <div class="top_slider next">
                    <button class="slider-nav next-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/271/271228.png" width="25" alt="Next">
                    </button>
                </div>
            </div>    
        </section>
<?php } ?>

<?php function drawListingsFromUser(array $listings, bool $bought) { ?> 
        <section id="top_listings_user">
            <div class="top_header">
                <?php if($bought) { ?>
                    <h1>Listings bought:</h1>
                <?php } else {?>
                    <h1>My Listings:</h1>
                <?php } ?>
                <div class="top_see_more">
                    <form>
                        <?php if($bought) { ?>
                            <button formaction="../pages/listings.php" formmethod="post" type="submit">
                                See More
                            </button>
                        <?php } else {?>
                            <button onclick="window.location.href='create_listing.php?'" type="button" class="confirmbtn">
                                Create
                            </button>
                            <button onclick="window.location.href='manage_offers.php?'" 
                                type="button" 
                                class="confirmbtn">
                            Clients
                            </button>
                            
                        <?php } ?>
                    </form>
                </div>
            </div>
            
            <div class="top_controls">
                <div class="top_slider prev">
                    <button class="slider-nav prev-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/271/271220.png" width="25" alt="Previous">
                    </button>
                </div>

                <div class="top_button">
                    <?php foreach($listings as $listing) { ?>
                    <form>
                        <input type="hidden" name="listing_id" value="<?= $listing->id ?>">
                        <button formaction="../pages/listing.php" formmethod="get" type="submit">
                            <img src="https://images.pexels.com/videos/8197836/pexels-photo-8197836.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                                width="150" alt="<?=$listing->id?>">
                            <p><?=$listing->title?></p>
                        </button>
                    </form>
                    <?php } ?>
                </div>
        
                <div class="top_slider next">
                    <button class="slider-nav next-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/271/271228.png" width="25" alt="Next">
                    </button>
                </div>
            </div>    
        </section>
<?php } ?>

<?php function drawCheckout(Listing $listing, User $provider, User $buyer, ?Order $order) { ?>
    <div class="listing">
        <div class="listing_left_side">
            <div class="billing_info">
                <h2>BILLING INFORMATION</h2>
                <label for="name"><b>Name</b></label>
                <input type="text" id="name" placeholder="First and Last Name" name="name" class="name" required>

                <label for="address"><b>Billing address</b></label>
                <input type="text" id="address" placeholder="Enter your address" name="address" class="address" required>

                <label for="address2"><b>Billing address, line 2</b></label>
                <input type="text" id="address2" placeholder="" name="address2" class="address2">

                <label for="country"><b>Country</b></label>
                    <select id="country" name="country" class="form-control">
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Åland Islands">Åland Islands</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antarctica">Antarctica</option>
                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Bouvet Island">Bouvet Island</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote D'ivoire">Cote D'ivoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Territories">French Southern Territories</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guernsey">Guernsey</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea-bissau">Guinea-bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Isle of Man">Isle of Man</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jersey">Jersey</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                    <option value="Korea, Republic of">Korea, Republic of</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macao">Macao</option>
                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montenegro">Montenegro</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Pitcairn">Pitcairn</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russian Federation">Russian Federation</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Helena">Saint Helena</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Timor-leste">Timor-leste</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Viet Nam">Viet Nam</option>
                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                </select>

                <label for="city"><b>City</b></label>
                <input type="text" id="city" placeholder="Enter your City" name="city" class="city">

                <label for="postal"><b>Zip or postal code</b></label>
                <input type="text" id="postal" placeholder="" name="postal" class="postal">
            </div>
            <div class="consent">
                <div class="consent">
                    <label>
                        <input type="checkbox" name="TOS" value="TOS" required>
                        <span>Do you accept and read through our <a href="../pages/secretTOS.php">Terms of Service</a></span>
                    </label>
                    <label>
                        <input type="checkbox" name="News" value="News">
                        <span>Do you consent to receive news from us in your email</span>
                    </label>
                    </div>
            </div>
            <div class="payment_option">
                <h2>Choose your payment option</h2>
                <button class="collapsible">
                    <div class="collapsible-button">
                        <span>Credit or Debit card</span>
                        <div class="icons">
                                <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                <img src="https://i.imgur.com/W1vtnOV.png" width="30">
                                <img src="https://i.imgur.com/35tC99g.png" width="30">
                                <img src="https://i.imgur.com/2ISgYja.png" width="30">
                        </div>
                    </div>
                </button>
                <div class="content">
                    <div class="content_inner">
                        <label for="ccard"><b>Card Number</b></label>
                        <div class="input">
                            <i class="fa fa-credit-card"></i>
                            <input type="text" id="cardnumber" placeholder="XXXX XXXX XXXX XXXX" name="cardnumber" class="card_control">
                        </div>
                        <div class="ccard_bottom_part">
                            <div class="ccard_left_side">
                                <label for="expiry_date"><b>Expiry Date</b></label>
                                <div class="input">
                                    <i class="fa fa-calendar"></i>
                                    <input type="text" id="expiry_date" placeholder="MM/YY" name="expiry_date" class="card_control">
                                </div>
                            </div>
                            <div class="ccard_right_side">
                                <label for="cvv"><b>CVV</b></label>
                                <div class="input">
                                    <i class="fa fa-lock"></i>
                                    <input type="text" id="cvv" placeholder="XXX" name="cvv" class="card_control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="collapsible">
                    <div class="collapsible-button">
                        <span>Paypal</span>
                        <img src="https://i.imgur.com/7kQEsHU.png" width="30">
                    </div>
                </button>
                <div class="content">
                    <div class="content_inner">
                            <input type="text" name="paypal_info" placeholder="Your paypal email" class="card_control">
                    </div>
                </div>
                <button class="collapsible">
                    <div class="collapsible-button">
                        <span>Gift Card</span>
                    </div>
                </button>
                <div class="content">
                    <div class="content_inner">
                            <input type="text" name="gift_info" placeholder="Your gift card code" class="card_control">
                    </div>
                </div>
            </div>
        </div>

        <div class="listing_right_side">
            <div class="order_info">
                <form action="../actions/action_process_checkout.php" method="get">
                    <input type="hidden" name="listing_id" value="<?=$listing->id?>">
                    <h2>Your Order</h2>
                    <div class="order_details">
                        <div class="order_image">
                            <img src="https://images.pexels.com/videos/8197836/pexels-photo-8197836.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                                        width="100" alt="<?=$listing->id?>">
                        </div>
                        <div class="order_title">
                            <p><?=$listing->title?></p>
                            <p>Expected delivery: <?=$listing->delivery_time?> days</p>
                        </div>
                    </div>

                    <div class="order_provider_info">
                        <h3>Provider Information</h3>
                        <p><?=$provider->name?></p>
                        <p><?=$provider->email?></p>
                        <p><?=$provider->phone?></p>
                    </div>
                    
                    <div class="order_info_price">
                        <div class="checkout_confirmation">
                            <div class="continue_button">
                                <button type="submit">
                                    Confirm
                                </button>
                            </div>
                        </div>
                        <p>Total: <?=$listing->price?>€</p>
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php } ?>

<?php function drawListingEditForm(Listing $listing) { ?>

    <div class="authform">
        <form action="../actions/action_edit_listing.php" method="post" enctype="multipart/form-data">
            <h2>Edit Listing: <?= htmlspecialchars($listing->title) ?></h2>
            
            <!-- Title Field -->
            <label for="title"><b>Title</b></label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($listing->title) ?>"
                   placeholder="Service title">

            <!-- Category Field -->
            <label for="category"><b>Category</b></label>
            <input type="text" id="category" name="category" required
                   value="<?= htmlspecialchars($listing->category) ?>"
                   placeholder="Service category">

            <!-- Price Field (with float fix) -->
            <label for="price"><b>Price ($)</b></label>
            <input type="number" id="price" name="price" step="0.01" min="0" required
                   value="<?= htmlspecialchars((string)$listing->price) ?>"
                   placeholder="00.00">

            <!-- Delivery Time -->
            <label for="delivery_time"><b>Delivery Time (days)</b></label>
            <input type="number" id="delivery_time" name="delivery_time" min="1" required
                   value="<?= htmlspecialchars((string)$listing->delivery_time) ?>"
                   placeholder="Estimated days">

            <!-- Description -->
            <label for="description"><b>Description</b></label>
            <textarea id="description" name="description" required
                      placeholder="Service details"><?= 
                      htmlspecialchars($listing->description) ?></textarea>


            <input type="hidden" name="listing_id" value="<?= $listing->id ?>">

            <!-- Buttons -->
            <div class="container">
                <button type="submit" class="confirmbtn">Save Changes</button>
                <button type="button" class="cancelbtn" 
                        onclick="window.location.href='../pages/listing.php?id=<?= $listing->id ?>'">
                    Cancel
                </button>
            </div>
        </form>
    </div>
<?php } ?>

<?php function drawCreateListing() { ?>
    <div class="authform">
        <form action="../actions/action_create_listing.php" method="post">
            <h2>Create New Listing</h2>
            
            <label for="title"><b>Title</b></label>
            <input type="text" id="title" name="title" required
                   placeholder="Service title">

            <label for="category"><b>Category</b></label>
            <input type="text" id="category" name="category" required
                   placeholder="Service category">

            <label for="price"><b>Price ($)</b></label>
            <input type="number" id="price" name="price" step="0.01" min="0" required
                   placeholder="00.00">

            <label for="delivery_time"><b>Delivery Time (days)</b></label>
            <input type="number" id="delivery_time" name="delivery_time" min="1" required
                   placeholder="Estimated days">

            <label for="description"><b>Description</b></label>
            <textarea id="description" name="description" required
                      placeholder="Service details"></textarea>

            <div class="container">
                <button type="submit" class="confirmbtn">Create Listing</button>
                <button type="button" class="cancelbtn" 
                        onclick="window.location.href='../pages/'">
                    Cancel
                </button>
            </div>
        </form>
    </div>
<?php } ?>