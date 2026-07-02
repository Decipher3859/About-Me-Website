const html = document.documentElement;

//--- THEME TOOGLE ---
const themeToggle = document.querySelector('#theme-toggle');
const savedTheme = localStorage.getItem('theme') || 'light';

html.setAttribute('data-theme', savedTheme);
updateThemeToggle(savedTheme);

themeToggle.addEventListener('click', () => {
  const currentTheme = html.getAttribute('data-theme');
  const newTheme = currentTheme === 'light' ? 'dark' : 'light';

  html.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);
  updateThemeToggle(newTheme);
})

function updateThemeToggle(theme) {
  const isDark = theme === 'dark';
  themeToggle.classList.toggle('theme-toggle--toggled', isDark);
  themeToggle.setAttribute('aria-label', isDark ? 'Change to light mode' : 'Change to dark mode');
  updateProfileImage(theme);
}

function updateProfileImage(theme) {
  const profileImage = document.querySelector('.profile-image');

  if (!profileImage) {
    return;
  }

  const imageSource = theme === 'dark'
    ? profileImage.dataset.profileDark
    : profileImage.dataset.profileLight;

  if (imageSource && profileImage.getAttribute('src') !== imageSource) {
    profileImage.setAttribute('src', imageSource);
  }
}

//--- PRIVACY BANNER ---
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

//--- SKILLTREE PROJECT GROUPS ---
document.querySelectorAll('.node-project-toggle').forEach((toggle) => {
  toggle.addEventListener('click', (event) => {
    event.preventDefault();
    event.stopPropagation();

    const projectList = toggle.nextElementSibling;
    if (!projectList) {
      return;
    }

    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!isExpanded));
    projectList.hidden = isExpanded;
  });
});

document.querySelectorAll('.node-project-list').forEach((projectList) => {
  projectList.addEventListener('click', (event) => {
    event.stopPropagation();
  });
});

document.querySelectorAll('.node-repo-link').forEach((repoLink) => {
  repoLink.addEventListener('click', (event) => {
    event.stopPropagation();
  });
});
