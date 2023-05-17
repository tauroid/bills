<?php

namespace App\Repositories;

use App\CallWithMiddleware;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Action {
    protected static function middleware(): array {
        return [];
    }

    protected abstract static function createRequest(
        ...$args
    ): mixed;

    protected static function getRelevantData(
        mixed $request
    ): mixed
    {
        return null;
    }

    protected static function getValidationRelevantData(
        mixed $request,
        mixed $relevantData
    ): mixed
    {
        return null;
    }

    protected static function getAuthorisationRelevantData(
        mixed $request,
        mixed $relevantData
    ): mixed
    {
        return null;
    }

    private static function getAllRelevantData(
        mixed $request
    ): Action\RelevantData
    {
        $relevantData = new Action\RelevantData;

        $relevantData->general =
            static::getRelevantData($request);
        $relevantData->validation =
            static::getValidationRelevantData(
                $request, $relevantData->general);
        $relevantData->authorisation =
            static::getAuthorisationRelevantData(
                $request, $relevantData->general);

        return $relevantData;
    }

    protected abstract static function validateRequest(
        mixed $request,
        mixed $validationRelevantData,
        mixed $generalRelevantData
    ): mixed;

    protected abstract static function authoriseRequest(
        mixed $request,
        mixed $authorisationRelevantData,
        mixed $generalRelevantData,
        mixed $requestValidationArtifacts
    ): mixed;

    protected static function toBeSaved(
        mixed $request,
        mixed $relevantData,
        mixed $requestValidationArtifacts,
        mixed $requestAuthorisationArtifacts
    ): array
    {
        return [];
    }

    protected static function deletions(
        mixed $request,
        mixed $relevantData,
        mixed $requestValidationArtifacts,
        mixed $requestAuthorisationArtifacts
    ): array
    {
        return [];
    }

    protected abstract static function validateChanges(
        array $toBeSaved,
        array $deletions
    ): mixed;

    protected static function applyChanges(
        array $toBeSaved,
        array $deletions
    ): void
    {
        if ($toBeSaved) {
            foreach ($toBeSaved as $model) {
                $model->saveComplete();
            }
        }
        if ($deletions) {
            foreach ($deletions as $deletion) {
                if (!in_array(SoftDeletes::class,
                            class_uses($deletion)))
                {
                    throw new ModelIsntSoftDeleteException(
                        get_class($deletion)
                    );
                }
                $deletion->delete();
            }
        }
    }

    protected static function response(
        mixed $request,
        mixed $relevantData,
        mixed $requestValidationArtifacts,
        mixed $requestAuthorisationArtifacts,
        array $toBeSaved,
        array $deletions,
        mixed $changesValidationArtifacts
    ): mixed
    {
        return null;
    }

    static function execute(...$args): mixed {
        return CallWithMiddleware::callWithMiddleware(
            static::middleware(),
            function () use ($args) {
                $request = static::createRequest(...$args);

                $relevantData =
                    static::getAllRelevantData($request);

                $requestValidationArtifacts =
                    static::validateRequest(
                        $request,
                        $relevantData->validation,
                        $relevantData->general);

                $requestAuthorisationArtifacts =
                    static::authoriseRequest(
                        $request,
                        $relevantData->authorisation,
                        $relevantData->general,
                        $requestValidationArtifacts
                    );

                $toBeSaved =
                    static::toBeSaved(
                        $request, $relevantData,
                        $requestValidationArtifacts,
                        $requestAuthorisationArtifacts
                    );

                $deletions = static::deletions(
                    $request, $relevantData,
                    $requestValidationArtifacts,
                    $requestAuthorisationArtifacts
                );

                $changesValidationArtifacts =
                    static::validateChanges(
                        $toBeSaved,
                        $deletions
                    );

                static::applyChanges(
                    $toBeSaved,
                    $deletions
                );

                return static::response(
                    $request,
                    $relevantData,
                    $requestValidationArtifacts,
                    $requestAuthorisationArtifacts,
                    $toBeSaved,
                    $deletions,
                    $changesValidationArtifacts
                );
            }
        );
    }
}
