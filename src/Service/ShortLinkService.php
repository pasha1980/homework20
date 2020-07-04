<?php


namespace App\Service;


use App\Entity\ShortLink;
use Doctrine\ORM\EntityManagerInterface;

class ShortLinkService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @param ContentInterface $contentObject
     * @return mixed
     */
    public function replaceLinks(ContentInterface $contentObject): void
    {
        preg_match_all('/http[s]?:\/\/[\w\.\/%-]+/', $contentObject->getContent(), $longLinks);

        foreach ($longLinks[0] as $longLink) {
            $shortLink = new ShortLink();
            $shortLink->setFullUrl($longLink);
            $shortLink->setShortCode($this->generateRandomString());

            $this->em->persist($shortLink);

            $contentObject->setContent(
                str_replace(
                    $longLink,
                    '<a href="http://localhost:8000/r/' . $shortLink->getShortCode() . '">http://localhost:8000/r/' . $shortLink->getShortCode() . '</a>',
                    $contentObject->getContent()
                )
            );
        }
    }

}