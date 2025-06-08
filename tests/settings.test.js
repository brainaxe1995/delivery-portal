const fs = require('fs');
const path = require('path');
const vm = require('vm');


describe('payment_terms.js', () => {
  let script;
  beforeAll(() => {
    script = fs.readFileSync(path.resolve(__dirname, '../assets/js/cJs/payment_terms.js'), 'utf8');
  });

  beforeEach(() => {
    document.body.innerHTML = '<table id="termsTable"><tbody></tbody></table><div id="termModal"></div><input id="termId"/><input id="termName"/><textarea id="termDesc"></textarea>';
    delete require.cache[require.resolve('jquery')];
    global.$ = require('jquery');
    $.fn.modal = jest.fn();
    global.BASE_URL = '';
    $.getJSON = jest.fn();
    $.ajax = jest.fn();
    vm.runInNewContext(script, global);
  });

  afterEach(() => {
    document.body.innerHTML = '';
    delete global.$;
    delete global.loadTerms;
    delete global.saveTerm;
  });

  test('loadTerms populates table rows', () => {
    $.getJSON.mockImplementation((url, cb) => cb([{id:1,name:'Net 30',description:'Pay in 30 days'}]));
    loadTerms();
    const rows = document.querySelectorAll('#termsTable tbody tr');
    expect(rows.length).toBe(1);
    expect(rows[0].textContent).toContain('Net 30');
  });

  test('saveTerm posts data and reloads', () => {
    const spyLoad = jest.spyOn(global, 'loadTerms');
    $.ajax.mockImplementation(opts => { if (opts.success) opts.success(); });
    $('#termId').val('2');
    $('#termName').val('Advance');
    $('#termDesc').val('Pay upfront');
    saveTerm();
    expect($.ajax).toHaveBeenCalled();
    const arg = $.ajax.mock.calls[0][0];
    expect(JSON.parse(arg.data)).toEqual({id:'2',name:'Advance',description:'Pay upfront'});
    expect($.fn.modal).toHaveBeenCalledWith('hide');
    expect(spyLoad).toHaveBeenCalled();
  });
});
