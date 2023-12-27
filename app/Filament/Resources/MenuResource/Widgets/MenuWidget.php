<?php

namespace App\Filament\Resources\MenuResource\Widgets;

use App\Models\Menu;
use Filament\Notifications\Notification;
use SolutionForest\FilamentTree\Actions\Action;
use SolutionForest\FilamentTree\Actions\ActionGroup;
use SolutionForest\FilamentTree\Actions\DeleteAction;
use SolutionForest\FilamentTree\Actions\EditAction;
use SolutionForest\FilamentTree\Actions\ViewAction;
use SolutionForest\FilamentTree\Widgets\Tree as BaseWidget;

class MenuWidget extends BaseWidget
{
    protected static string $model = Menu::class;

    protected static int $maxDepth = 7;

    protected ?string $treeTitle = 'Menu Items';

    protected bool $enableTreeTitle = true;

    protected function getFormSchema(): array
    {
        return [
            //
        ];
    }

    // INFOLIST, CAN DELETE
    public function getViewFormSchema(): array
    {
        return [
            //
        ];
    }

    // CUSTOMIZE ICON OF EACH RECORD, CAN DELETE


    // CUSTOMIZE ACTION OF EACH RECORD, CAN DELETE 
    protected function getTreeActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
            // ActionGroup::make([

            //     ViewAction::make(),
            //     EditAction::make(),
            // ]),
            DeleteAction::make(),
        ];
    }
    protected function hasDeleteAction(): bool
    {
        return true;
    }
    protected function hasEditAction(): bool
    {
        return true;
    }
    protected function hasViewAction(): bool
    {
        return true;
    }
}
