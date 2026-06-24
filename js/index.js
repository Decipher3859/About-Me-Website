const privacyBanner = document.querySelector('[data-privacy-banner]');
const privacyBannerButton = document.querySelector('[data-privacy-banner-button]');

if (privacyBanner && privacyBannerButton) {
  const privacyBannerSeen = sessionStorage.getItem('privacyBannerSeen') === 'true';

  if (privacyBannerSeen) {
    privacyBanner.hidden = true;
  }

  privacyBannerButton.addEventListener('click', () => {
    sessionStorage.setItem('privacyBannerSeen', 'true');
    privacyBanner.hidden = true;
  })
}

