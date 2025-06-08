describe('dynamic-pie-chart.js', () => {
  beforeEach(() => {
    document.body.innerHTML = '<div class="pie-chart" data-percentage="75" data-diameter="80"></div>';
    HTMLCanvasElement.prototype.getContext = () => ({
      beginPath: jest.fn(),
      arc: jest.fn(),
      stroke: jest.fn()
    });
    delete require.cache[require.resolve('../assets/js/dynamic-pie-chart.js')];
    require('../assets/js/dynamic-pie-chart.js');
  });

  afterEach(() => {
    document.body.innerHTML = '';
  });

  test('creates canvas elements', () => {
    const canvas = document.querySelector('.pie-chart canvas');
    expect(canvas).not.toBeNull();
    const span = document.querySelector('.pie-chart__percentage');
    expect(span).not.toBeNull();
  });
});
