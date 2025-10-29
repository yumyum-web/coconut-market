# Coconut Market - Coconut Farming Marketplace

A modern web platform connecting coconut farmers with buyers, built with Laravel, Vue.js, and Tailwind CSS.

## Features

### For Farmers
- **Profile Management**: Maintain detailed farmer profiles including farm information, experience, and location
- **Plot Management**: Register and manage coconut plots with details about size, tree count, harvest potential, and delivery options
- **Photo Albums**: Upload up to 10 images per plot to showcase your farm
- **Harvest Management**: Log actual harvests and schedule future ones
- **Bidding System**: Start timed bids for harvests with configurable time windows (1 hour, 1 day, etc.)
- **Flexible Categories**: Sell harvests in multiple categories - husked (per nut), unhusked (per nut), or scraped (per kg)
- **Products & Byproducts**: List processed products (oil, desiccated coconut) and byproducts (husks, shells, coir) for sale
- **Rating System**: Rate buyers after successful transactions

### For Buyers
- **Browse Marketplace**: View available harvests from farmers
- **Farmer Profiles**: View detailed farmer profiles, plots, and harvest history
- **Smart Bidding**: Place bids on one or more harvest categories
- **Winning Algorithm**: Harvests awarded to the buyer offering highest total profit to the farmer
- **Product Marketplace**: Browse and bid on products and byproducts
- **Rating System**: Rate farmers and harvests after winning bids

## Tech Stack

- **Backend**: Laravel 11 with PHP 8.2+
- **Frontend**: Vue 3 with TypeScript
- **Styling**: Tailwind CSS v4 with green-themed palette
- **UI Components**: shadcn-vue
- **Internationalization**: vue-i18n (English locale included)
- **Authentication**: Laravel Fortify with 2FA support
- **Database**: MySQL
- **Build Tool**: Vite

## Database Schema

### Core Tables
- `users` - User accounts with type (farmer/buyer)
- `farmer_profiles` - Farmer-specific profile data
- `buyer_profiles` - Buyer-specific profile data
- `plots` - Coconut plot information
- `plot_images` - Plot photo albums
- `harvests` - Harvest records with bidding information
- `harvest_bids` - Bids placed on harvests
- `harvest_categories` - Available harvest categories (husked, unhusked, scraped)
- `products` - Processed products for sale
- `byproducts` - Byproducts for sale
- `product_bids` - Bids on products
- `byproduct_bids` - Bids on byproducts
- `ratings` - User ratings (polymorphic)
- `bid_time_windows` - Pre-configured bid durations

## Installation

1. **Clone the repository** (or you're already in it)

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**:
   ```bash
   npm install
   ```

4. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database** in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=coconut_market
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**:
   ```bash
   php artisan migrate:fresh
   ```

7. **Storage link**:
   ```bash
   php artisan storage:link
   ```

8. **Build assets**:
   ```bash
   npm run build
   ```

9. **Start development server**:
   ```bash
   composer run dev
   ```

## Development

### Running the application
```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Build and watch assets
npm run dev
```

### Project Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php      # Farmer & Buyer dashboards
│   ├── ProfileController.php        # Profile management
│   ├── PlotController.php           # Plot CRUD
│   ├── HarvestController.php        # Harvest management & bidding
│   ├── HarvestBidController.php     # Bid placement
│   ├── ProductController.php        # Product management
│   ├── ByproductController.php      # Byproduct management
│   └── RatingController.php         # Rating system
├── Models/
│   ├── User.php
│   ├── FarmerProfile.php
│   ├── BuyerProfile.php
│   ├── Plot.php
│   ├── PlotImage.php
│   ├── Harvest.php
│   ├── HarvestBid.php
│   ├── Product.php
│   ├── Byproduct.php
│   ├── ProductBid.php
│   ├── ByproductBid.php
│   ├── Rating.php
│   ├── HarvestCategory.php
│   └── BidTimeWindow.php
└── Actions/Fortify/
    └── CreateNewUser.php           # Registration with user type

resources/js/
├── pages/
│   ├── Dashboard/
│   │   ├── FarmerDashboard.vue
│   │   └── BuyerDashboard.vue
│   └── auth/
│       └── Register.vue            # Updated with user type selection
├── i18n/
│   ├── index.ts                    # i18n configuration
│   └── locales/
│       └── en.json                 # English translations
└── layouts/
    └── AppLayout.vue

routes/
└── web.php                         # All application routes

database/migrations/
├── 2025_10_29_*_add_user_type_to_users_table.php
├── 2025_10_29_*_create_farmer_profiles_table.php
├── 2025_10_29_*_create_buyer_profiles_table.php
├── 2025_10_29_*_create_plots_table.php
├── 2025_10_29_*_create_plot_images_table.php
├── 2025_10_29_*_create_harvest_categories_table.php
├── 2025_10_29_*_create_bid_time_windows_table.php
├── 2025_10_29_*_create_harvests_table.php
├── 2025_10_29_*_create_harvest_bids_table.php
├── 2025_10_29_*_create_products_table.php
├── 2025_10_29_*_create_byproducts_table.php
├── 2025_10_29_*_create_product_bids_table.php
├── 2025_10_29_*_create_byproduct_bids_table.php
└── 2025_10_29_*_create_ratings_table.php
```

## Theme

The application uses a green-themed color palette that aligns with the agricultural/coconut farming context:

- **Light Mode**: Fresh green accents with light backgrounds
- **Dark Mode**: Deep green accents with dark backgrounds
- **Primary Color**: Emerald green (hsl(142 76% 36%))
- **Responsive**: Fully responsive design for mobile, tablet, and desktop

## Internationalization

The application is set up with vue-i18n for easy localization:

- English locale is fully implemented
- Easy to add more languages by creating new JSON files in `resources/js/i18n/locales/`
- All UI text uses translation keys

## Key Features Implementation Status

✅ **Completed**:
- Database schema and migrations
- All Eloquent models with relationships
- User authentication with Farmer/Buyer types
- Profile management system
- Plot management (CRUD operations)
- Harvest management with bidding system
- Product and byproduct listing
- Rating system structure
- Dashboard for farmers and buyers
- Green theme implementation
- i18n setup
- Route structure

🚧 **To Implement**:
- Complete Vue components for:
  - Plot management pages
  - Harvest creation/management pages
  - Bidding interface
  - Product/byproduct pages
  - Profile edit pages
  - Rating submission forms
- Form validation rules (Request classes)
- Image upload handling
- Bid winner selection algorithm
- Real-time bid updates (consider Laravel Echo)
- Email notifications
- Search and filtering
- Comprehensive test suite

## API Endpoints

### Authentication
- `POST /register` - Register as farmer or buyer
- `POST /login` - Login
- `POST /logout` - Logout

### Farmer Routes
- `GET /dashboard` - Farmer dashboard
- `GET /profile` - View profile
- `PUT /profile` - Update profile
- `GET /plots` - List plots
- `POST /plots` - Create plot
- `GET /plots/{id}` - View plot
- `PUT /plots/{id}` - Update plot
- `DELETE /plots/{id}` - Delete plot
- `POST /harvests` - Create harvest
- `POST /harvests/{id}/start-bid` - Start bidding
- `POST /harvests/{id}/select-winner` - Select winning bid

### Buyer Routes
- `GET /dashboard` - Buyer dashboard
- `GET /farmers/{id}` - View farmer profile
- `GET /harvests` - Browse available harvests
- `POST /harvests/{id}/bids` - Place bid
- `GET /harvest-bids` - View my bids

### Common Routes
- `GET /harvests/{id}` - View harvest details
- `POST /ratings` - Submit rating

## Next Steps

1. **Complete Vue Components**: Implement remaining pages for full CRUD operations
2. **Add Policies**: Create authorization policies for resource access control
3. **Form Requests**: Create validation classes for all forms
4. **Image Upload**: Implement file upload for plot images
5. **Notifications**: Add email/in-app notifications for bid updates
6. **Testing**: Write feature and unit tests
7. **Real-time Updates**: Consider adding WebSocket support for live bidding
8. **Search & Filters**: Add advanced search for harvests, farmers, products
9. **Analytics Dashboard**: Add charts and statistics
10. **Mobile Optimization**: Ensure excellent mobile UX

## Contributing

When contributing, please:
1. Follow Laravel and Vue.js best practices
2. Use TypeScript for new Vue components
3. Add translations to `en.json` for any new text
4. Write tests for new features
5. Keep the green theme consistent

## License

[Your License Here]

## Support

For issues and questions, please create an issue in the repository.
