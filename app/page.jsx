import Image from 'next/image'

export default function Home() {
  return (
    <main>
      <h1>Hello</h1>
      <Image
        src="bbwheroes.svg"
        width={100}
        height={100}
      />
    </main>
  )
}
