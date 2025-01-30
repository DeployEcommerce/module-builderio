![Static Badge](https://img.shields.io/badge/compatible-compatible?style=for-the-badge&label=Hyv%C3%A4&labelColor=%230A144B&color=%230A23B9%20)

# BuilderIO Integration for Magento 2

This Magento 2 module integrates **Builder.io** into your Magento store, enabling you to manage CMS pages and category headings effortlessly with Builder.io's visual editor.

### 🎉 Hyvä compatibility

BuilderIO compatible with Hyvä theme. No comapatibility module is needed.

### Luma compatibility

It may be needed to run in fallback mode for the editor as we have experienced some issues with the Luma theme and general performace/lag.

## Features
- **CMS Page Integration:** Manage and design your CMS pages directly from Builder.io.

## Local Development
To use this module locally, you need to expose your local environment to the internet. You can use tools like [ngrok](https://ngrok.com/) or [cloudfare tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/) to expose your local environment to the internet.

A great article to get you going:
https://www.twilio.com/en-us/blog/expose-localhost-to-internet-with-tunnel

This will enable the Builder.io webhook to communicate with your local environment.

## Requirements

- Magento 2.4.x
- PHP 8.1 or higher
- Active Builder.io account

## Installation
Install the module via Composer:
```bash
composer require deployecommerce/module-builderio
bin/magento setup:upgrade
```

## Contributing

We welcome contributions from the community! Here's how you can help:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Commit your changes and push the branch.
4. Submit a pull request to the main repository.

## Support

For issues or feature requests, please open a GitHub issue in this repository.

## License

This project is open-source and available under the OSL v3 License. See the LICENSE file for more details.

### Happy building! 🚀
