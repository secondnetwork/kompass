
import './plugins/lite-yt-embed'
import './plugins/lite-vimeo-embed'

function syncTheme() {
    document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') === 'dark' ? 'dark' : 'light')
}

document.addEventListener('livewire:navigated', syncTheme)

function syncHeaderHeight() {
    const header = document.querySelector('header.navbar')
    if (header) {
        document.documentElement.style.setProperty('--header-h', header.offsetHeight + 'px')
    }
}

function initHeaderOffset() {
    syncHeaderHeight()

    if (location.hash) {
        document.querySelector(location.hash)?.scrollIntoView()
    }
}

window.addEventListener('load', initHeaderOffset)
window.addEventListener('resize', syncHeaderHeight)
document.addEventListener('livewire:navigated', syncHeaderHeight)
