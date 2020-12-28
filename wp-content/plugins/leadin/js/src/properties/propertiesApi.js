export function getContactProperties() {
  return window.leadinChildFrameConnection.promise.then(child =>
    child.getContactProperties()
  );
}
