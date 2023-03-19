export const getFullUrl = (relativeUrl: string) => {
  return new URL(relativeUrl, import.meta.url).href
}