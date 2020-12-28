import './src/styles/init.less'

(function () {
  const headerFooterLayout = '.vcv-content--header-footer'
  const blankLayout = '.vcv-content--blank'
  if (window.vcv) {
    window.vcv.on('ready', function () {
      const fullWidthElements = Array.prototype.slice.call(document.querySelectorAll('[data-vce-full-width="true"]:not([data-vcv-do-helper-clone]):not([data-vce-stretch-content]),[data-vce-full-width-section="true"]:not([data-vcv-do-helper-clone]):not([data-vce-section-stretch-content])'))
      if (fullWidthElements.length) {
        fullWidthElements.forEach(function (element) {
          if (element.closest(headerFooterLayout) || element.closest(blankLayout)) {
            const elementContent = element.querySelector('[data-vce-element-content="true"]')
            if (elementContent) {
              elementContent.style['padding-left'] = ''
              elementContent.style['padding-right'] = ''
            }
          }
        })
      }
    })
  }
})()
