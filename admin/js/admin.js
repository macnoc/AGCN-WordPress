function displayAdminNotice(message, type = 'error') {
    // Find or create notices container
    let noticesContainer = document.querySelector('.agcn-notices');
    if (!noticesContainer) {
        noticesContainer = document.createElement('div');
        noticesContainer.className = 'agcn-notices';
        const h1Tag = document.querySelector('h1');
        if (h1Tag) {
            h1Tag.parentNode.insertBefore(noticesContainer, h1Tag.nextSibling);
        }
    }

    // Create notice element
    const notice = document.createElement('div');
    notice.className = `notice notice-${type} is-dismissible`;
    notice.innerHTML = `
        <p>${message}</p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    `;

    // Add dismiss functionality
    notice.querySelector('.notice-dismiss').addEventListener('click', () => {
        notice.remove();
    });

    // Add notice to container
    noticesContainer.appendChild(notice);
}