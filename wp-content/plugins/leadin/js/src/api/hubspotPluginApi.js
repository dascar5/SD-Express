export function getAuth() {
  return window.leadinChildFrameConnection.promise.then(child =>
    child.leadinGetAuth()
  );
}

export function searchForms(searchQuery = '') {
  return window.leadinChildFrameConnection.promise.then(child =>
    child.leadinSearchForms(searchQuery)
  );
}

export function getForm(formId) {
  return window.leadinChildFrameConnection.promise.then(child =>
    child.leadinGetForm(formId)
  );
}

export function monitorFormPreviewRender() {
  return window.leadinChildFrameConnection.promise.then(child =>
    child.monitorFormPreviewRender()
  );
}
