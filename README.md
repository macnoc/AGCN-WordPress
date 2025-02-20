![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)
![WordPress: 5.2+](https://img.shields.io/badge/WordPress-5.2%2B-blue.svg)
![PHP: 7.2+](https://img.shields.io/badge/PHP-7.2%2B-blue.svg)
![Accessibility: Yes](https://img.shields.io/badge/Accessibility-Yes-blue.svg)
![Responsive: Yes](https://img.shields.io/badge/Responsive-Yes-blue.svg)
![Multi-language: Yes](https://img.shields.io/badge/Multi--language-Yes-blue.svg)
![Gutenberg: Yes](https://img.shields.io/badge/Gutenberg-Yes-blue.svg)
![Page Builders: Yes](https://img.shields.io/badge/Page%20Builders-Yes-blue.svg)

# AGCN (AI-Generated Content Notifier) for WordPress

## Description

AGCN is a WordPress plugin that seamlessly integrates with your website to inform visitors about AI-generated content. Built on top of the [AGCN JavaScript widget](https://github.com/macnoc/AGCN), this plugin provides an easy-to-use interface for WordPress administrators to manage AI content notifications across their site.

### Key Features

- **Easy Configuration**: Simple admin interface to manage all settings
- **Multi-language Support**: Built-in support for multiple languages
- **Gutenberg Integration**: Native block editor support with custom block attributes
- **Customizable Styling**: Extensive styling options through the WordPress admin
- **Responsive Design**: Works seamlessly on all devices and screen sizes
- **Accessibility Ready**: WCAG 2.1 compliant with full keyboard navigation support

## Installation

### Automatic Installation
1. Log in to your WordPress dashboard
2. Navigate to Plugins → Add New
3. Search for "AGCN"
4. Click "Install Now" and then "Activate"

### Manual Installation
1. Download the latest release from the [WordPress plugin repository]() or [GitHub releases]()
2. Extract the downloaded ZIP file
3. Rename the extracted folder to `agcn`
4. Upload the `agcn` folder to the `/wp-content/plugins/` directory
5. Activate the plugin through the 'Plugins' menu in WordPress

## Configuration

### Basic Setup
1. Navigate to Settings → AGCN Settings in your WordPress admin
2. Configure the following tabs:
   - **Configuration**: Basic plugin settings
   - **Content Management**: Manage notification content
   - **Styling**: Customize the appearance

### Available Settings

#### Configuration Tab
- Language Selection
- Badge Position (top-left, top-right, bottom-left, bottom-right)
- Show/Hide Badge
- Support Attribution

#### Content Management Tab
- Header Text
- Modal Title
- Modal Body Content
- Section Management
  - Add/Remove Sections
  - Custom Notice Text
  - Section Titles and Content

#### Styling Tab
- Color Customization
- Dark Mode Support
- Badge Styling
- Modal Styling
- Notice Styling

## Usage

### Basic Implementation
The plugin automatically adds necessary scripts and styles to your WordPress site. The AI content badge will appear according to your configuration settings.

### Gutenberg Blocks
1. Select any block in the Gutenberg editor
2. Open the block settings sidebar
3. Find the "AI Content" section
4. Select the appropriate AI content type

## Requirements

- WordPress 5.2 or higher
- PHP 7.2 or higher
- Modern browsers with ES6 support

## Frequently Asked Questions

### Is this plugin GDPR compliant?
Yes, AGCN does not collect any personal data and is fully GDPR compliant.

### Can I use this with page builders?
Yes, AGCN works with most popular page builders including Elementor, Divi, and WPBakery.

### Does it affect site performance?
AGCN is lightweight and optimized for performance. The JavaScript bundle is only 41KB minified and gzipped.

## Support

- [Documentation](https://github.com/macnoc/AGCN-wordpress)
- [GitHub Issues](https://github.com/macnoc/AGCN-wordpress/issues)

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Credits

- Built with [AGCN JavaScript Widget](https://github.com/macnoc/AGCN)
- Created by [Macnoc](https://macnoc.com)