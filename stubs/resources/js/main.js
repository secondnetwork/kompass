
import './plugins/lite-yt-embed'
import './plugins/lite-vimeo-embed'

function syncTheme() {
    document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') === 'dark' ? 'dark' : 'light')
}

document.addEventListener('livewire:navigated', syncTheme)