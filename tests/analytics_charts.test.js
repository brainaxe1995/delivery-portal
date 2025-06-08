const fs = require('fs');
const path = require('path');
const vm = require('vm');
const { JSDOM } = require('jsdom');

describe('analytics.js charts', () => {
  const scriptPath = path.resolve(__dirname, '../assets/js/cJs/analytics.js');
  test('creates weekly and monthly charts', () => {
    const dom = new JSDOM('<!doctype html><html><body><canvas id="chartWeek"></canvas><canvas id="chartMonth"></canvas></body></html>');
    const context = dom.window;
    context.BASE_URL = '';
    const $ = fn => { if (typeof fn === 'function') fn(); return { length:0, text:()=>{}, append:()=>{}, empty:()=>{} }; };
    $.getJSON = jest.fn((url, cb) => cb({
      chart_data: { labels: ['A','B'], revenue: [1,2], orders: [3,4] },
      chart1: { labels: ['Jan','Feb'], revenue: [5,6] }
    }));
    context.$ = context.jQuery = $;
    context.Chart = jest.fn();
    Object.defineProperty(context.document, 'readyState', { configurable: true, value: 'complete' });
    const fn = new Function('window','document','$','Chart','BASE_URL', fs.readFileSync(scriptPath,'utf8'));
    fn(context, context.document, context.$, context.Chart, context.BASE_URL);
    expect(context.Chart).toHaveBeenCalledTimes(2);
    const weekCfg = context.Chart.mock.calls[0][1];
    expect(weekCfg.data.labels).toEqual(['A','B']);
    expect(weekCfg.data.datasets[0].data).toEqual([1,2]);
    expect(weekCfg.data.datasets[1].data).toEqual([3,4]);
    const monthCfg = context.Chart.mock.calls[1][1];
    expect(monthCfg.data.labels).toEqual(['Jan','Feb']);
    expect(monthCfg.data.datasets[0].data).toEqual([5,6]);
  });
});
