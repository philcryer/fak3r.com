import type { IconMap, SocialLink, Site } from '@/types'

export const SITE: Site = {
  title: 'fak3r',
  description: "It's a good life if you don't weaken.",
  href: 'https://fak3r.com',
  author: 'fak3r',
  locale: 'en-US',
  featuredPostCount: 8,
  postsPerPage: 8,
}

export const NAV_LINKS: SocialLink[] = [
  { href: '/about', label: 'about' },
  { href: '/blog', label: 'blog' },
  { href: '/contact', label: 'contact' },
  // { href: '/authors', label: 'authors' },
  { href: '/tags', label: 'tags' },
]

export const SOCIAL_LINKS: SocialLink[] = [
  { href: 'https://github.com/philcryer/fak3r.com', label: 'GitHub' },
  { href: 'https://mastodon.social/@fak3r', label: 'Mastodon' },
  { href: 'mailto:fak3r @ fak3r . com', label: 'Email' },
  { href: '/rss.xml', label: 'RSS' },
]

export const ICON_MAP: IconMap = {
  Website: 'lucide:globe',
  GitHub: 'lucide:github',
  LinkedIn: 'lucide:linkedin',
  Twitter: 'lucide:twitter',
  Email: 'lucide:mail',
  RSS: 'lucide:rss',
}
