# Simple Redirect Manager

Manage URL redirects effortlessly in WordPress. Add, view, and delete redirects with support for multiple HTTP redirect types directly from the admin dashboard. It supports multiple types of HTTP redirects, including 301, 302, 303, 307, and 308.

## Features

- Add source and target URLs for redirects.
- Choose the type of redirect (301, 302, 303, 307, 308).
- View all redirects in a table format.
- Delete redirects from the admin page.
- Automatically handle frontend redirection based on the defined rules.

## Installation

1. Download the plugin file.
2. Upload the file to the `/wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.
4. Navigate to **Redirect Manager** in the WordPress admin menu to start managing redirects.

## Usage

1. Go to **Redirect Manager** in the WordPress admin menu.
2. Add a redirect by entering the following details:
   - **Source URL**: The URL to redirect from.
   - **Target URL**: The URL to redirect to.
   - **Redirect Type**: Select the type of redirect (e.g., 301, 302).
3. View existing redirects in the table.
4. Delete redirects by clicking the "Delete" button next to an entry.

## Redirect Types Supported

- **301 - Moved Permanently**: Use for permanent URL changes.
- **302 - Found (Temporary Redirect)**: Use for temporary redirects.
- **303 - See Other**: Redirect with a GET request.
- **307 - Temporary Redirect**: Temporary redirect while maintaining the request method.
- **308 - Permanent Redirect**: Permanent redirect while maintaining the request method.

## Database Table

The plugin creates a custom database table named `wp_redirects` to store the following fields:

- `id`: Auto-incremented ID.
- `source_url`: The URL to redirect from.
- `target_url`: The URL to redirect to.
- `redirect_type`: The type of redirect (301, 302, etc.).

## Frontend Behavior

The plugin automatically checks incoming requests and redirects users based on the rules defined in the admin panel. If a match is found, the user is redirected to the target URL with the specified HTTP status code.

## Development Notes

- Use `wp_redirect()` to handle redirects securely.
- Always call `exit;` after `wp_redirect()` to ensure no further processing occurs.

## Screenshots

1. **Admin Page**: Add and view redirects.
2. **Table View**: Display of existing redirects with a delete option.

## Changelog

### 1.0
- Initial release.

## License

This plugin is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

