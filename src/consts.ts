export type Site = {
  TITLE: string
  DESCRIPTION: string
  EMAIL: string
  NUM_POSTS_ON_HOMEPAGE: number
  POSTS_PER_PAGE: number
  SITEURL: string
}

export type Link = {
  href: string
  label: string
}

export const SITE: Site = {
  TITLE: 'fak3r',
  DESCRIPTION: "It's a good life if you don't weaken",
  EMAIL: 'fak3r @ fak3r . com',
  NUM_POSTS_ON_HOMEPAGE: 5,
  POSTS_PER_PAGE: 5,
  SITEURL: 'https://fak3r.com',
}

export const NAV_LINKS: Link[] = [
  { href: '/blog', label: 'blog' },
  // { href: '/authors', label: 'authors' },
  { href: '/about', label: 'about' },
  { href: '/tags', label: 'tags' },
]

export const SOCIAL_LINKS: Link[] = [
  { href: 'https://github.com/philcryer/fak3r.com', label: 'GitHub' },
  { href: 'https://mastodon.social/@fak3r', label: 'Mastodon' },
  { href: 'fak3r @ fak3r . com', label: 'Email' },
  { href: '/rss.xml', label: 'RSS' },
]
