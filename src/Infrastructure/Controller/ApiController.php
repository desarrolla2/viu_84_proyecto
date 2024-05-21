<?php

namespace App\Infrastructure\Controller;

use App\Domain\Generator\Entity\Document as GeneratorDocument;
use App\Domain\Generator\Service\GeneratorEngine;
use App\Domain\Reader\Entity\DummyAgreement;
use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text as ReaderText;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    public function __construct(private readonly GeneratorEngine $generatorEngine, private readonly ReaderEngine $readerEngine)
    {
    }

    #[Route('/upload', name: '_app.api.upload')]
    public function upload(Request $request,): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $file = base64_decode($data['file']);

        $fileName = sprintf('%s.%s', hash('sha256', uniqid(get_called_class(), true)), pathinfo($data['name'], PATHINFO_EXTENSION));
        $path = sprintf('%s/%s', sys_get_temp_dir(), $fileName);

        try {
            file_put_contents($path, $file);
        } catch (FileException $exception) {
            return new JsonResponse(['code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => 'Document type could not be processed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $generatorDocument = new GeneratorDocument($path);
        $text = $this->generatorEngine->execute($generatorDocument);

        $readerText = new ReaderText($text->content());
        $agreement = $this->readerEngine->execute($readerText);

        unlink($path);

        if ($agreement instanceof DummyAgreement) {
            return new JsonResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Document type could not be determined'], Response::HTTP_BAD_REQUEST);
        }

        $parties = array_reduce($agreement->parties(), function (array $carry, Person $person): array {
            $carry[] = sprintf('%s, %s', $person->name(), $person->number());

            return $carry;
        }, []);

        return new JsonResponse(
            ['code' => Response::HTTP_OK, 'document' => (new \ReflectionClass($agreement))->getShortName(), 'date' => $agreement->date()?->format('d/m/Y'), 'parties' => $parties], Response::HTTP_OK
        );
    }
}
