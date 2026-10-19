# Kredit Komponen UI

Beberapa komponen antarmuka pada proyek ini diadaptasi dari [Uiverse.io](https://uiverse.io/).
Seluruh elemen Uiverse.io dipublikasikan dengan lisensi **MIT**.

## Komponen yang digunakan

| Komponen | Kreator | Sumber | Digunakan pada |
| --- | --- | --- | --- |
| Green Add Button | andrew-demchenk0 | [mighty-moose-66](https://uiverse.io/andrew-demchenk0/mighty-moose-66) | Tombol CTA utama (beranda & donasi) — `.uv-btn` |
| Success Alert Card | kennyotsu | [short-ape-5](https://uiverse.io/kennyotsu/short-ape-5) | Notifikasi flash sukses — `.uv-alert--success` |
| Warning Alert Card | kennyotsu | [fast-emu-70](https://uiverse.io/kennyotsu/fast-emu-70) | Notifikasi peringatan — `.uv-alert--warning` |
| Emerald Switch | nikk7007 | [smooth-fox-6](https://uiverse.io/nikk7007/smooth-fox-6) | Toggle "Kampanye unggulan" di form admin — `.uv-switch` |
| Animated Checkbox | elijahgummer | [friendly-wombat-2](https://uiverse.io/elijahgummer/friendly-wombat-2) | Checkbox donasi anonim — `.uv-check` |
| Search Input | alexruix | [plastic-shrimp-54](https://uiverse.io/alexruix/plastic-shrimp-54) | Pencarian kampanye & artikel — `.uv-search` |
| Soft Green Pattern | AspenBranch | [slippery-sheep-50](https://uiverse.io/AspenBranch/slippery-sheep-50) | Latar hero beranda — `.uv-pattern` |
| Bounce Tooltip | gharsh11032000 | [spotty-zebra-83](https://uiverse.io/gharsh11032000/spotty-zebra-83) | Tooltip tombol aksi tabel admin — `.uv-tip` |
| Green Ripple Loader | alexruix | [fluffy-starfish-52](https://uiverse.io/alexruix/fluffy-starfish-52) | Indikator proses pada tombol donasi — `.uv-loader` |

## Modifikasi

Komponen disesuaikan dengan tema putih bersih dan emerald proyek:

- Warna asli diganti ke token emerald: `#059669`, `#047857`, `#10B981`, `#ECFDF5`, `#D1FAE5`, `#065F46`.
- Selector diberi prefix `.uv-` agar tidak bentrok dengan Tailwind atau komponen lain.
- Animasi dibatasi dan mendukung `prefers-reduced-motion`.
- Ditambahkan state fokus keyboard (`:focus-visible`) dan label aksesibilitas.

## Lisensi MIT

```text
MIT License

Copyright (c) Uiverse.io contributors

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
