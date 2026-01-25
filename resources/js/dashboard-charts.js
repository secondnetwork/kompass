import ApexCharts from 'apexcharts';

function getCssColor(varName) {
  const val = getComputedStyle(document.documentElement).getPropertyValue(varName);
  return val ? val.trim() : null;
}

function getSparklineOptions(data, color, type = 'area') {
  return {
    series: [{ data }],
    chart: {
      type: type,
      height: 60,
      sparkline: { enabled: true },
      animations: { enabled: true }
    },
    stroke: {
      curve: 'smooth',
      width: 3,
      colors: [color]
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.45,
        opacityTo: 0.05,
        stops: [20, 100, 100],
      },
      colors: [color]
    },
    tooltip: { enabled: false },
    colors: [color]
  };
}

function initDashboardCharts() {
  const labels = window.chartLabels || [];
  const pages = window.chartPages || [];
  const pagesPub = window.chartPagesPublished || [];
  const posts = window.chartPosts || [];
  const postsPub = window.chartPostsPublished || [];
  const mediaCount = window.mediaCount || 0;
  const mediaData = posts.map(v => Math.round(v * 0.8));
  const activity = pages.map((v, i) => v + (posts[i] ?? 0));

  const primary = getCssColor('--p') ? `oklch(${getCssColor('--p')})` : 'rgb(59, 130, 246)';
  const success = getCssColor('--s') ? `oklch(${getCssColor('--s')})` : 'rgb(34, 197, 94)';

  const renderChart = (id, options, chartKey) => {
    const el = document.getElementById(id);
    if (el) {
      el.innerHTML = '';
      if (window[chartKey]) window[chartKey].destroy();
      window[chartKey] = new ApexCharts(el, options);
      window[chartKey].render();
    }
  };
  renderChart('chartPagesSparkline', getSparklineOptions(pages, primary), 'PagesSparkChart');
  renderChart('chartPostsSparkline', getSparklineOptions(posts, primary), 'postsSparkChart');
  renderChart('chartMediaSparkline', getSparklineOptions(mediaData, primary, 'line'), 'mediaSparkChart');

  const activityOpts = getSparklineOptions(activity, primary, 'bar');
  activityOpts.plotOptions = { bar: { borderRadius: 2, columnWidth: '60%' } };
  renderChart('chartActivitySparkline', activityOpts, 'activitySparkChart');

  // Area Chart (Pages)
  const areaOpts = {
    series: [{ name: 'Pages', data: pages }],
    chart: { type: 'area', height: 60, sparkline: { enabled: true } },
    stroke: { curve: 'smooth', width: 2 },
    colors: [primary],
    fill: { type: 'gradient', gradient: { opacityFrom: 0.45, opacityTo: 0.05 } }
  };
  renderChart('area-chart', areaOpts, 'areaChart');

  // Grid Chart (Combined Comparison)
  const gridOpts = {
    series: [
      { name: 'Published Pages', data: pagesPub },
      { name: 'Published Posts', data: postsPub }
    ],
    chart: { type: 'area', height: 100, sparkline: { enabled: true } },
    stroke: { curve: 'smooth', width: 2 },
    colors: [primary, success],
    fill: { type: 'gradient', gradient: { opacityFrom: 0.45, opacityTo: 0.05 } },
    tooltip: { enabled: true, theme: 'dark' }
  };
  renderChart('grid-chart', gridOpts, 'gridChart');
}

document.addEventListener('livewire:navigated', initDashboardCharts);
document.addEventListener('DOMContentLoaded', initDashboardCharts);

window.initDashboardCharts = initDashboardCharts;

export { initDashboardCharts };
